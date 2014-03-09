<?php

namespace M2T\Commands;

class CheckTorrent extends BaseCommand {

	protected $name = 'm2t:check';

	protected $description = 'Checks that the specified torrent has all its metadata. If it does, populate the files/trackers/total size';

	public function fire() {
		$this->validateHash();
		if ($torrent = $this->getTorrent()) {
			$hash = $torrent->getInfoHash();
			if ($this->backend->isMetainfoComplete($torrent)) {
				$this->backend->getMetainfoAndFiles($torrent);
				$this->torrents->persist($torrent);
				$this->info("Updated $hash with metainfo and files");
			} else {
				$this->info("Did not update $hash as metadata is not yet complete");
			}
		}
	}
}