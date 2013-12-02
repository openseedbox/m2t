<?php

namespace M2T\Commands;

use M2T\Models\TorrentInterface;
use Symfony\Component\Console\Input\InputArgument;

use \DB;

class CollectStats extends BaseCommand {
	
	protected $name = 'm2t:stats';

	protected $description = 'Loops over all the known torrents and grabs their seeder/leecher/complete stats';

	public function fire() {
		$hash = $this->argument("hash");
		$self = $this;
		if ($hash && $torrent = $this->getTorrent()) {
			$this->updateTrackersFor($torrent);
		} else {
			$torrents = $this->torrents->all();
			DB::transaction(function() use ($torrents, $self) {
				foreach ($torrents as $torrent) {
					$self->updateTrackersFor($torrent);
				}
			});
			$this->info("All stats updated.");
		}
	}

	public function getArguments() {
		return array(
			array('hash', InputArgument::OPTIONAL, 'The torrent info_hash. If omitted, all torrents will have their stats updated.'),
		);
	}

	private function updateTrackersFor(TorrentInterface $torrent) {
		$this->transmission->getTrackerStats($torrent);
		$torrent->save();
		$this->info("Updated tracker stats for: {$torrent->getInfoHash()}");
	}
	
}