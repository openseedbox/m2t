<?php

namespace M2T\Controllers;

use M2T\Models\TorrentRepositoryInterface;

class InfoController extends BaseController {

	public function getIndex($hash) {		
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

	public function getRecent() {
		return $this->success($torrent->getRecent(10)->toArray());
	}

	private function errorInvalidHash($hash) {
		return $this->error("Hash empty or invalid: $hash");
	}

}