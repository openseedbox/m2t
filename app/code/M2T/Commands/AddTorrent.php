<?php

namespace M2T\Commands;

class AddTorrent extends BaseCommand {

	protected $name = 'm2t:add';

	protected $description = 'Adds a torrent to transmission based on a hash already in the database';

	public function fire() {
		$this->info("Hash is: {$this->argument('hash')}");
		$torrent = $this->getTorrent();		
		if ($torrent) {
			$this->info("Adding torrent {$torrent->getInfoHash()} to transmission...");
			$opts = array(
				"download-dir" => "/dev/null"
			);
			if ($torrent->isFromMagnet()) {
				$this->transmission->add($torrent->getMagnetUri(), false, $opts);
				$this->call("m2t:check", array("hash" => $torrent->getInfoHash()));
			} else {
				$opts["paused"] = true;
				$this->transmission->add($torrent->getBase64Metadata(), true, $opts);
			}
			$torrent->in_transmission = true; //TODO: use TorrentInterface method instead of coupling to Eloquent implementation
			$torrent->save();
		} else {
			$this->error("Couldnt find torrent for: {$this->argument('hash')}");
		}
	}
	
}