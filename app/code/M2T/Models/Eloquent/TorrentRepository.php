<?php

namespace M2T\Models\Eloquent;

use M2T\Util\DataHandler;
use M2T\Models\TorrentRepositoryInterface;
use M2T\Models\Eloquent\Torrent as EloquentTorrent;

use Openseedbox\Parser\Torrent as TorrentParser;
use Openseedbox\Parser\Magnet as MagnetParser;
use Openseedbox\Parser\TorrentInterface as TorrentParserInterface;
use Guzzle\Http\Client as HttpClient;

use \DB, \Queue;

class TorrentRepository implements TorrentRepositoryInterface {

	private $handler;

	public function __construct(DataHandler $handler, MagnetParser $magnet_parser, TorrentParser $torrent_parser, HttpClient $client, EloquentTorrent $torrent) {
		$this->handler = $handler;
		$this->magnet_parser = $magnet_parser;
		$this->torrent_parser = $torrent_parser;
		$this->torrent = $torrent;
	}

	public function findByHash($hash) {
		return $this->torrent->where("hash", $hash)->limit(1)->first();
	}

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

	public function all() {
		return $this->torrent->all();
	}	

	private function addFromHash($hash) {
		return $this->addFromMagnet($this->magnet_parser->create($hash));
	}

	private function addFromMagnet($magnet) {
		$magnet = $this->magnet_parser->parse($magnet);
		return $this->createFromParsed($magnet);
	}

	private function addFromUrl($url) {
		$response = $client->get($url)->send();
		echo("got response");
		dd($response);
	}

	private function addFromBase64($base64) {
		$file = base64_decode($base64);
		$torrent = $this->torrent_parser->parseFromContents($file);
		return $this->createFromParsed($torrent);
	}

	private function createFromParsed(TorrentParserInterface $torrent) {
		$data = array(
			"name" => $torrent->getName(),
			"hash" => $torrent->getInfoHash()			
		);

		if ($torrent->isFromMagnet()) {
			$data["magnet_uri"] = $torrent->getMagnetUri();
		} else {
			$data["base64_metadata"] = $torrent->getMetadataBase64();
			$data["total_size_bytes"] = $torrent->getTotalSizeBytes();
		}

		$ret = $this->findByHash($torrent->getInfoHash());
		if ($ret) {
			$ret->update($data);
		} else {
			$ret = $this->torrent->create($data);
		}

		$ret->clearTrackers();

		$trackers = $torrent->getTrackerUrls();
		if (count($trackers) > 0) {

			DB::transaction(function() use ($trackers, $ret) {
				foreach ($trackers as $tracker_url) {
					Tracker::create(array(
						"torrent_id" => $ret->id,
						"tracker_url" => $tracker_url
					));
				}
			});
		}

		//queue a job to get the metadata if required
		/*
		if (!$ret->in_transmission) {			
			Queue::push("jobs.add_torrent", array("hash" => $ret->getInfoHash()));
		}*/

		return $ret;
	}

}