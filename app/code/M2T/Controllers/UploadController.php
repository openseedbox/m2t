<?php

namespace M2T\Controllers;

class UploadController extends BaseController {	

	public function getIndex($data = "") {
		$data = trim($data);
		$error_message = "Please specify a magnet link, url, hash or base64 data.";
		if ($data) {
			$torrent = $this->torrents->add($data);
			if ($torrent) {
				return $this->success(array(
					"data" => $data,
					"added" => true,
					"hash" => $torrent->getInfoHash()
				));
			}
			$error_message = "The supplied data wasnt recognised as a megnet link, url, hash or base64";
		}
		return $this->error($error_message);
	}

}