<?php

namespace M2T\Models\Eloquent;

use M2T\Util\DataHandler;
use M2T\Models\TorrentRepositoryInterface;

class TorrentRepository implements TorrentRepositoryInterface {

	private $handler;

	public function __construct(DataHandler $handler) {
		$this->handler = $handler;
	}

	public function getByHash($hash) {
		return Torrent::where("hash", $hash)->limit(1)->first();
	}

	public function addFromHash($hash) {

	}

	public function addFromMagnet($magnet) {

	}

	public function addFromUrl($url) {

	}

	public function addFromBase64($base64) {

	}

	public function add($data) {
		if ($this->handler->isHash($data)) {
			return $this->addFromHash($data);
		}
		if ($this->handler->isMagnet($data)) {
			return $this->addFromMagnet($data);
		}
		if ($this->handler->isUrl($data)) {
			return $this->addFromUrl($data);
		}
		if ($this->handler->isBase64($data)) {
			return $this->addFromBase64($data);
		}
		return null;
	}

}