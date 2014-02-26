<?php

namespace M2T\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use M2T\Models\TorrentRepositoryInterface;
use M2T\Backends\BackendInterface;

abstract class BaseCommand extends Command {

	protected $torrents;
	protected $transmission;

	public function __construct(TorrentRepositoryInterface $torrents, BackendInterface $backend) {
		parent::__construct();
		$this->torrents = $torrents;
		$this->backend = $backend;
	}

	protected function getArguments() {
		return array(
			array('hash', InputArgument::REQUIRED, 'The torrent info_hash'),
		);
	}

	protected function validateHash() {
		$validator = \Validator::make(array("hash" => $this->getHash()), array("hash" => "required|valid_hash"));
		if ($validator->fails()) {
			$this->abortWithError($validator->messages()->first());
		}
	}

	protected function getTorrent() {
		$hash = $this->getHash();
		$torrent = $this->torrents->findByHash($hash);
		if (!$torrent) {
			$this->abortWithError("No such torrent with hash: $hash");
		}
		return $torrent;
	}

	protected function getHash() {
		return $this->argument("hash");
	}

	protected function abortWithError($message) {
		throw new \RuntimeException($message);
	}

}