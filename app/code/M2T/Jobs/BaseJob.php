<?php

namespace M2T\Jobs;

use M2T\Models\TorrentRepositoryInterface;

abstract class BaseJob {

	public function __construct(TorrentRepositoryInterface $torrents) {
		$this->torrents = $torrents;
		$this->transmission = \App::make("transmission");
	}

	protected function getTorrent($data) {
		return $this->torrents->findByHash($data["hash"]);
	}

}