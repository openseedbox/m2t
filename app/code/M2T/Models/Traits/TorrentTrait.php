<?php

namespace M2T\Models\Traits;

use \URL;
use M2T\Util\ByteFormatter;

trait TorrentTrait {

	public function getDownloadLink() {
		return URL::route("metadata.hash", array("hash" => $this->getInfoHash()));
	}

	public function toArray() {
		$formatter = new ByteFormatter();
		$data = array(
			"has-metadata" => $this->hasMetadata(),
			"hash" => $this->getInfoHash(),
			"name" => $this->getName()
		);
		if (!$this->hasMetadata()) {
			return $data;
		}
		return array_merge($data, array(
			"download-link" => $this->getDownloadLink(),
			"total-size-bytes" => $this->getTotalSizeBytes(),
			"total-size-human" => $formatter->format($this->getTotalSizeBytes()),
			"files" => $this->getFiles()->toArray(),
			"trackers" => $this->getTrackers()->toArray()
		));
	}

}