<?php

namespace M2T\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use M2T\Models\TorrentRepositoryInterface;

abstract class BaseCommand extends Command {

	protected $torrents;
	protected $transmission;

	public function __construct(TorrentRepositoryInterface $torrents) {
		parent::__construct();
		$this->torrents = $torrents;
		$this->transmission = \App::make("transmission");
	}

	protected function getArguments() {
		return array(
			array('hash', InputArgument::REQUIRED, 'The torrent info_hash'),
		);
	}

	protected function getTorrent() {
		return $this->torrents->findByHash($this->argument("hash"));
	}

	protected function createTracker($torrent, $tracker) {
		$t = $torrent->newTracker();
		$t->setTrackerUrl($tracker["host"]);
		$t->setSeedCount($tracker["seederCount"]);
		$t->setLeecherCount($tracker["leecherCount"]);
		$t->setCompletedCount($tracker["downloadCount"]);
		$t->setMessage($tracker["lastAnnounceResult"]);		
		return $t;
	}
	
}