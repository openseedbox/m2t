<?php

namespace M2T\Jobs;

use M2T\Models\TorrentRepositoryInterface;

use \App, \Log, \Queue;

class AddTorrentJob extends BaseJob {

	public function fire($job, $data) {		
		$torrent = $this->getTorrent($data);
		if ($torrent) {
			Log::info("Adding torrent {$torrent->getInfoHash()} to transmission...");
			$opts = array(
				"download-dir" => "/dev/null"
			);
			if ($torrent->isFromMagnet()) {
				$this->transmission->add($torrent->getMagnetUri(), false, $opts);
				Queue::push("jobs.monitor_torrent", array("hash" => $torrent->getInfoHash()));
			} else {
				$opts["paused"] = true;
				$this->transmission->add($torrent->getBase64Metadata(), true, $opts);
			}
			$torrent->in_transmission = true; //TODO: use TorrentInterface method instead of coupling to Eloquent implementation
			$torrent->save();
		} else {
			Log::warn("Couldnt find torrent for: {$torrent->getInfoHash()}");
		}
		$job->delete();
	}

}