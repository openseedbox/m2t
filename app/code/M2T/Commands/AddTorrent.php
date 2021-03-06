<?php

namespace M2T\Commands;

class AddTorrent extends BaseCommand {

	protected $name = 'm2t:add';

	protected $description = 'Adds a torrent to transmission based on a hash already in the database';

	public function fire() {
		$this->validateHash();
		$hash = $this->getHash();
		if ($torrent = $this->getTorrent()) {
			$this->info("Adding torrent with hash: $hash");
			$this->backend->addTorrent($torrent);
		} else {
			$this->error("No such torrent with hash: $hash");
		}
	}

}