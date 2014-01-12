<?php

namespace M2T\Models\Traits;

trait TorrentTrait {

	public function getDownloadLink() {
		return "to implement";
	}

	public function toArray() {		
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
			"files" => $this->getFiles()->toArray(),
			"trackers" => $this->getTrackers()->toArray()
		));
	}

}