<?php

namespace M2T\Controllers;

use M2T\Models\TorrentRepositoryInterface;
use \Validator;

class InfoController extends BaseController {

	public function __construct(TorrentRepositoryInterface $torrents) {
		$this->torrents = $torrents;
	}

	public function getIndex($hash) {
		die(print_r(\Config::get("transmission"), true));
		$validator = $this->getValidator($hash);
		if ($validator->fails()) {
			return $this->error($validator);
		}
		$torrent = $this->torrents->findByHash($hash);
		if (!$torrent) {
			return $this->errorInvalidHash($hash);
		}
		return $this->success($torrent->toArray());
	}

	private function getValidator($hash) {
		return Validator::make(array(
				"hash" => $hash
			), array(
				"hash" => "required|valid_hash|hash_in_db"
		));
	}

	private function errorInvalidHash($hash) {
		return $this->error("Hash empty or invalid: $hash");
	}

}