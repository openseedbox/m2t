<?php

namespace M2T\Controllers;

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

	private function errorInvalidHash($hash) {
		return $this->error("Hash empty or invalid: $hash");
	}

}