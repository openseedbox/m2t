<?php

namespace M2T\Controllers;

use M2T\Models\TorrentRepositoryInterface;

class UploadController extends BaseController {

	protected $torrents;

	public function __construct(TorrentRepositoryInterface $torrents) {
		parent::__construct();
		$this->torrents = $torrents;
	}

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