<?php

namespace M2T\Backends;

use M2T\Models\TorrentInterface;
use Vohof\Transmission as TransmissionRPC;
use \Config;

class Transmission implements BackendInterface {

	private $tracker_stats = null;

	public function __construct() {
		$this->transmission = new TransmissionRPC(Config::get("transmission"));
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
		if (!$this->torrentPresentIn($response)) {
			$this->addTorrent($torrent);
			return $this->isMetainfoComplete($torrent);
		}
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
		if (!$this->torrentPresentIn($response)) {
			$this->addTorrent($torrent);
			return null;
		}

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
		if ($stats = $this->getTrackerStatsFor($torrent->getInfoHash())) {
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
		} else {
			//if there were no stats, the torrent probably wasnt added. Add it.
			$this->addTorrent($torrent);
		}

		return $torrent;
	}

	private function torrentPresentIn(array $response) {
		return count($response["torrents"]) > 0;
	}

	private function getTrackerStatsFor($hash) {
		if (!$this->tracker_stats) {
			$this->tracker_stats = $this->transmission->get("all", array("hashString", "trackerStats"));
		}
		foreach ($this->tracker_stats["torrents"] as $torrent) {
			if ($torrent["hashString"] == $hash) {
				return $torrent["trackerStats"];
			}
		}
	}

}