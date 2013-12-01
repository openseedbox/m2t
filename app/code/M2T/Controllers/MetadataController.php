<?php

namespace M2T\Controllers;

use M2T\Models\TorrentRepositoryInterface;

use \Response;

class MetadataController extends BaseController {

	public function getIndex($hash) {
		$validator = $this->getValidator($hash);
		if ($validator->fails()) { return $this->error($validator); }
		$torrent = $this->getTorrent($hash);
		return $this->success(array(
			"hash" => $hash,
			"name" => $torrent->getName(),
			"base64_metadata" => $torrent->getBase64Metadata()
		));
	}

	public function getFile($hash) {
		$validator = $this->getValidator($hash);
		if ($validator->fails()) { return $this->error($validator); }
		$torrent = $this->getTorrent($hash);
		$name = urlencode($torrent->getName());

		return Response::make(base64_decode($torrent->getBase64Metadata()), 200, array(
			"Content-Type" => "application/x-bittorrent",
			"Content-Disposition" => "attachment; filename={$name}.torrent"
		));		
	}

}