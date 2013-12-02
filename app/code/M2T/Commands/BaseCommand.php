<?php

namespace M2T\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use M2T\Models\TorrentRepositoryInterface;
use M2T\Backends\Transmission as TransmissionBackend;

abstract class BaseCommand extends Command {

	protected $torrents;
	protected $transmission;

	public function __construct(TorrentRepositoryInterface $torrents, TransmissionBackend $transmission) {
		parent::__construct();
		$this->torrents = $torrents;
		$this->transmission = $transmission;
	}

	protected function getArguments() {
		return array(
			array('hash', InputArgument::REQUIRED, 'The torrent info_hash'),
		);
	}

	protected function getTorrent() {
		return $this->torrents->findByHash($this->argument("hash"));
	}
	
}