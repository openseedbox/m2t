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
		return $this->success(array("torrent" => $torrent->toArray()));
	}

	public function getRecent() {
		return $this->success(array("torrents" => $this->torrents->getRecent(10)->toArray()));
	}

	public function getRefresh($hash) {
		\Artisan::call("m2t:check", array("hash" => $hash));
		\Artisan::call("m2t:stats", array("hash" => $hash));
		return $this->success(array("refreshed" => $hash));
	}

}