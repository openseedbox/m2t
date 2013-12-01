<?php

namespace M2T\Commands;

class CheckTorrent extends BaseCommand {

	protected $name = 'm2t:check';

	protected $description = 'Checks that the specified torrent has all its metadata. If it does, populate the files/trackers/total size';

	public function fire() {
		$torrent = $this->getTorrent();
		if (!$torrent) {
			return;
		}
		$response = $this->transmission->get($torrent->getInfoHash(), array("metadataPercentComplete"));
		$complete = $response["torrents"][0]["metadataPercentComplete"];
		if ($complete == 1) {
			$this->info("Torrent {$torrent->getInfoHash()} metadata is complete, getting info.");
			$response = $this->transmission->get($torrent->getInfoHash(), array("name", "totalSize", "files", "trackerStats", "metainfo"));			
			$this->transmission->action("stop", $torrent->getInfoHash()); //stop wasting bandwidth by downloading to /dev/null
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

			$torrent->clearTrackers();
			foreach ($t["trackerStats"] as $tracker) {				
				$torrent->addTracker($this->createTracker($torrent, $tracker));
			}

			$torrent->save();			
		} else {
			$this->info("Torrent {$torrent->getInfoHash()} doesnt have all its metadata yet.");
		}	
	}
}