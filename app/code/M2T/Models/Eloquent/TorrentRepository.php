<?php

namespace M2T\Models\Eloquent;

use M2T\Util\DataHandler;
use M2T\Models\TorrentRepositoryInterface;
use M2T\Models\TorrentInterface;
use M2T\Models\Eloquent\Torrent as EloquentTorrent;

use Openseedbox\Parser\Torrent as TorrentParser;
use Openseedbox\Parser\Magnet as MagnetParser;
use Openseedbox\Parser\TorrentInterface as TorrentParserInterface;
use Guzzle\Http\Client as HttpClient;

use \DB, \Queue;

class TorrentRepository implements TorrentRepositoryInterface {

	private $handler;

	public function __construct(DataHandler $handler, MagnetParser $magnet_parser, TorrentParser $torrent_parser, HttpClient $client) {
		$this->handler = $handler;
		$this->magnet_parser = $magnet_parser;
		$this->torrent_parser = $torrent_parser;
		$this->client = $client;
	}

	/**
	 * @inheritdoc
	 */
	public function findByHash($hash) {
		return EloquentTorrent::where("hash", $hash)->limit(1)->first();
	}

	/**
	 * @inheritdoc
	 */
	public function add($data) {
		if ($this->handler->isHash($data)) {
			return $this->addFromHash($data);
		}
		if ($this->handler->isMagnet($data)) {
			return $this->addFromMagnet($data);
		}
		if ($this->handler->isUrl($data)) {
			return $this->addFromUrl($data);
		}
		if ($this->handler->isBase64($data)) {
			return $this->addFromBase64($data);
		}
		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function all() {
		return EloquentTorrent::all();
	}

	/**
	 * @inheritdoc
	 */
	public function getRecent($limit = 10) {
		return $this->torrents->orderBy("created_at", "DESC")->take($limit)->get();
	}

	private function addFromHash($hash) {
		return $this->addFromMagnet($this->magnet_parser->create($hash));
	}

	private function addFromMagnet($magnet) {
		$magnet = $this->magnet_parser->parse($magnet);
		return $this->createFromParsed($magnet);
	}

	private function addFromUrl($url) {
		$response = $this->client->get($url)->send();
		$data = $response->getBody(true);
		return $this->createFromParsed($this->torrent_parser->parse($data));
	}

	private function addFromBase64($base64) {
		$file = base64_decode($base64);
		$torrent = $this->torrent_parser->parse($file);
		return $this->createFromParsed($torrent);
	}

	private function createFromParsed(TorrentParserInterface $torrent) {
		$data = $this->getDataArray($torrent);

		$ret = $this->createOrUpdate($data);

		$this->addTrackersIfPresent($torrent, $ret);

		$this->queueAddToBackend($ret);
		
		return $ret;
	}

	private function getDataArray(TorrentParserInterface $torrent) {
		$data = array(
			"name" => $torrent->getName(),
			"hash" => $torrent->getInfoHash()			
		);

		if ($torrent->isFromMagnet()) {
			$data["magnet_uri"] = $torrent->getMagnetUri();
		} else {
			$data["base64_metadata"] = $torrent->getBase64Metadata();
			$data["total_size_bytes"] = $torrent->getTotalSizeBytes();
		}

		return $data;
	}

	private function createOrUpdate(array $data) {
		$ret = $this->findByHash($data["hash"]);
		if ($ret) {
			$ret->update($data);
		} else {
			$ret = EloquentTorrent::create($data);
		}
		return $ret;
	}

	private function addTrackersIfPresent(TorrentParserInterface $torrent, TorrentInterface $ret) {
		$ret->clearTrackers();

		$trackers = $torrent->getTrackerUrls();
		if (count($trackers) > 0) {
			DB::transaction(function() use ($trackers, $ret) {
				foreach ($trackers as $tracker_url) {
					$tracker = $ret->newTracker();
					$tracker->setTrackerUrl($tracker_url);
					$ret->addTracker($tracker);					
				}
			});
		}

		return $ret;
	}

	private function queueAddToBackend(TorrentInterface $ret) {
		//queue a job to get the metadata if required
		/*
		if (!$ret->in_transmission) {			
			Queue::push("jobs.add_torrent", array("hash" => $ret->getInfoHash()));
		}*/
	}

}