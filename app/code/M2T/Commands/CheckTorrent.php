<?php

namespace M2T\Commands;

class CheckTorrent extends BaseCommand {

	protected $name = 'm2t:check';

	protected $description = 'Checks that the specified torrent has all its metadata. If it does, populate the files/trackers/total size';

	public function fire() {
		$this->validateHash();
		if ($torrent = $this->getTorrent()) {
			$hash = $torrent->getInfoHash();
			if ($this->transmission->isMetainfoComplete($torrent)) {
				$this->transmission->getMetainfoAndFiles($torrent);
				$torrent->save();
				$this->info("Updated $hash with metainfo and files");
			}
		}
	}
}