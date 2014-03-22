<?php

namespace M2T\Controllers;

use M2T\Models\TorrentInterface;

class UploadController extends BaseController {

	public function getIndex() {
		$data = trim(\Input::get("url"));
		$error_message = "Please specify a magnet link, url, hash or base64 data.";
		if ($data) {
			$torrent = $this->torrents->add($data);
			if ($torrent) {
				$this->queueCheckTorrent($torrent);
				return $this->success(array(
					"data" => $data,
					"added" => true,
					"hash" => $torrent->getInfoHash()
				));
			}
			$error_message = "The supplied data wasnt recognised as a magnet link, url, hash or base64";
		}
		return $this->error($error_message, array("data" => $data));
	}

	private function queueCheckTorrent(TorrentInterface $torrent) {
		\Queue::push(function($job) use ($torrent) {
			\Artisan::call("m2t:check", array("hash" => $torrent->getInfoHash()));
		});
	}

}