<?php

namespace M2T\Jobs;

use \Log;

class MonitorTorrentJob extends BaseJob {

	public function fire($job, $data) {
		$torrent = $this->getTorrent($data);
		if (!$torrent) {
			return $job->delete();			
		}
		$response = $this->transmission->get($torrent->getInfoHash(), array("metadataPercentComplete"));
		$complete = $response["torrents"][0]["metadataPercentComplete"];
		if ($complete == 1) {
			Log::info("Torrent {$torrent->getInfoHash()} metadata is complete, getting info.");
			$response = $this->transmission->get($torrent->getInfoHash(), array("name", "totalSize", "files", "trackers", "trackerStats", "metainfo"));
			$this->transmission->action("pause", $torrent->getInfoHash()); //stop wasting bandwidth by downloading to /dev/null
			$t = $response["torrents"][0];

			//TODO: use TorrentInterface methods
			$torrent->base64_metadata = $t["metainfo"];
			$torrent->name = $t["name"];
			$torrent->total_size_bytes = $t["totalSize"];
			foreach ($torrent["files"] as $file) {
				//add files
			}
			foreach ($torrent["trackers"] as $tracker) {
				//add trackers
			}
			$torrent->save();
			$job->delete();
		}
	}

}