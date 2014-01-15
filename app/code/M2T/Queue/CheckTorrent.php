<?php

namespace M2T\Queue;

use M2T\Models\TorrentInterface;
use \Artisan;

class CheckTorrent {

	public function fire($job, $data) {
		$torrent = @$data["torrent"];
		if ($torrent && $torrent instanceof TorrentInterface) {
			$hash = $torrent->getInfoHash();
			Artisan::call("m2t:check", array("hash" => $hash));
		}
	}

}