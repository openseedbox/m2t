<?php

namespace M2T\Commands;

use M2T\Backends\TorrentNotPresentException;

class CheckTorrent extends BaseCommand {

	protected $name = 'm2t:check';

	protected $description = 'Checks that the specified torrent has all its metadata. If it does, populate the files/trackers/total size';

	public function fire() {
		$this->validateHash();
		if ($torrent = $this->getTorrent()) {
			$hash = $torrent->getInfoHash();
			try {
				if ($this->backend->isMetainfoComplete($torrent)) {
					$this->backend->getMetainfoAndFiles($torrent);
					$this->torrents->persist($torrent);
					$this->info("Updated $hash with metainfo and files");
				} else {
					$this->info("Did not update $hash as metadata is not yet complete");
				}
			} catch (TorrentNotPresentException $ex) {
				$this->call("m2t:add", array("hash" => $torrent->getInfoHash()));
			}
		}
	}
}