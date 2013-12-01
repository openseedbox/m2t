<?php

namespace M2T\Commands;

class CollectStats extends BaseCommand {
	
	protected $name = 'm2t:stats';

	protected $description = 'Loops over all the known torrents and grabs their seeder/leecher/complete stats';

	public function fire() {
		$torrents = $this->transmission->get("all", array("hashString", "trackerStats"));		
		foreach ($torrents["torrents"] as $torrent) {
			$db = $this->torrents->findByHash($torrent["hashString"]);
			if ($db) {
				$db->clearTrackers();
				foreach ($torrent["trackerStats"] as $tracker) {
					$db->addTracker($this->createTracker($db, $tracker));
				}
				$db->save();
				$this->info("Torrent {$torrent['hashString']} stats updated.");
			} else {
				$this->error("Torrent {$torrent['hashString']} is in transmission but not in the database! Removing from transmission.");
				$this->transmission->remove($torrent["hashString"], true);
			}
		}
	}

	public function getArguments() {
		return array();
	}
	
}