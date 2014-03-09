<?php

namespace M2T\Backends;

use M2T\Models\TorrentInterface;
use Illuminate\Support\Collection;
use Vohof\Transmission as TransmissionRPC;
use \Config;

class Transmission implements BackendInterface {

	public function __construct($transmission = null) {
		if (!$transmission) {
			$this->transmission = new TransmissionRPC(Config::get("transmission"));
		} else {
			$this->transmission = $transmission;
		}
	}

	/**
	 * @inheritDoc
	 */
	public function addTorrent(TorrentInterface $torrent) {
		$opts = array(
			"download-dir" => "/dev/null"
		);
		if ($torrent->isFromMagnet()) {
			$this->transmission->add($torrent->getMagnetUri(), false, $opts);
		} else {
			$opts["paused"] = true;
			$this->transmission->add($torrent->getBase64Metadata(), true, $opts);
		}
		return $torrent;
	}

	/**
	 * @inheritDoc
	 */
	public function isMetainfoComplete(TorrentInterface $torrent) {
		$response = $this->transmission->get($torrent->getInfoHash(), array("metadataPercentComplete"));

		$this->throwExceptionIfTorrentNotPresentIn($response, $torrent);

		$complete = $response["torrents"][0]["metadataPercentComplete"];
		if ($complete == 1) {
			$this->transmission->action("stop", $torrent->getInfoHash()); //stop wasting bandwidth by downloading to /dev/null
		}
		return ($complete == 1);
	}

	/**
	 * @inheritDoc
	 */
	public function getMetainfoAndFiles(TorrentInterface $torrent) {
		$response = $this->transmission->get($torrent->getInfoHash(), array("name", "totalSize", "files", "metainfo"));

		$this->throwExceptionIfTorrentNotPresentIn($response, $torrent);

		$t = $response["torrents"][0];
		$torrent->setBase64Metadata($t["metainfo"]);
		$torrent->setName($t["name"]);
		$torrent->setTotalSizeBytes($t["totalSize"]);

		$torrent->clearFiles();
		foreach ($t["files"] as $file) {
			$f = $torrent->newFile();
			$f->setName(basename($file["name"]));
			$f->setLengthBytes($file["length"]);
			$f->setFullLocation($file["name"]);
			$torrent->addFile($f);
		}

		return $torrent;
	}

	/**
	 * @inheritDoc
	 */
	public function getTrackerStats(TorrentInterface $torrent) {
		$response = $this->transmission->get($torrent->getInfoHash(), array("trackerStats"));

		$this->throwExceptionIfTorrentNotPresentIn($response, $torrent);

		$stats = $response["torrents"][0]["trackerStats"];

		$this->populateTorrentWithTrackerStats($stats, $torrent);

		return $torrent;
	}

	/**
	 * @inheritDoc
	 */
	public function getTrackerStatsForMultiple(Collection $torrents) {
		$hashes = array();

		$torrents->each(function($torrent) use (&$hashes) {
			$hashes[] = $torrent->getInfoHash();
		});

		$response = $this->transmission->get($hashes, array("hashString", "trackerStats"));

		foreach ($response["torrents"] as $responseTorrent) {
			$match = $torrents->filter(function($torrent) use ($responseTorrent) {
				return ($responseTorrent["hashString"] == $torrent->getInfoHash());
			})->first();
			if ($match) {
				$this->populateTorrentWithTrackerStats($responseTorrent["trackerStats"], $match);
			}
		}

		return $torrents;
	}

	private function throwExceptionIfTorrentNotPresentIn(array $response, TorrentInterface $torrent) {
		$present = count($response["torrents"]) > 0;
		if (!$present) {
			throw new TorrentNotPresentException("Torrent {$torrent->getInfoHash()} is not present in the backend.");
		}
	}

	private function populateTorrentWithTrackerStats(array $stats, TorrentInterface $torrent) {
		$torrent->clearTrackers();
		foreach ($stats as $tracker) {
			$t = $torrent->newTracker();
			$t->setTrackerUrl($tracker["host"]);
			$t->setSeedCount($tracker["seederCount"]);
			$t->setLeecherCount($tracker["leecherCount"]);
			$t->setCompletedCount($tracker["downloadCount"]);
			$t->setMessage($tracker["lastAnnounceResult"]);
			$torrent->addTracker($t);
		}
		return $torrent;
	}

}