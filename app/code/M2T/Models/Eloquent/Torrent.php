<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TorrentInterface;
use \Eloquent, \DB;

class Torrent extends Eloquent implements TorrentInterface {

	protected $table = "torrents";

	protected $fillable = array("hash", "name", "total_size_bytes", "base64_metadata", "magnet_uri");
	protected $hidden = array("in_transmission", "updated_at");

	public function files() {
		return $this->hasMany(get_class(new File()));
	}

	public function trackers() {
		return $this->hasMany(get_class(new Tracker()));
	}

	public function getHasMetadataAttribute() {
		return !is_null($this->base64_metadata);
	}

	public function getInfoHash() {
		return $this->hash;
	}

	public function getName() {
		return $this->name;
	}

	public function getTotalSizeBytes() {
		return $this->total_size_bytes;
	}

	public function getBase64Metadata() {
		return $this->base64_metadata;
	}

	public function hasMetadata() {
		return $this->has_metadata;
	}

	public function getCreateDate() {
		return $this->created_at;
	}	

	public function getTrackers() {
		return $this->trackers()->get();
	}

	public function getFiles() {
		return $this->files()->get();
	}

	public function getDownloadLink() {
		return "to implement";
	}

	public function getMagnetUri() {
		return $this->magnet_uri;
	}

	public function isFromMagnet() {
		return !is_null($this->magnet_uri);
	}

	public function clearTrackers() {
		$trackers = $this->getTrackers();		
		DB::transaction(function() use ($trackers) {
			$trackers->each(function($tracker) {
				$tracker->delete();
			});
		});
	}

	public function toArray() {
		$data = array(
			"has_metadata" => $this->hasMetadata(),
			"hash" => $this->getInfoHash(),
			"name" => $this->getName(),
			"from_magnet" => $this->isFromMagnet()
		);
		if ($this->isFromMagnet()) {
			$data["magnet_uri"] = $this->getMagnetUri();
		}
		if (!$this->hasMetadata()) {
			return $data;
		}		
		return array_merge($data, array(						
			"download_link" => $this->getDownloadLink(),
			"total_size_bytes" => $this->getTotalSizeBytes(),
			"files" => $this->getFiles()->toArray(),
			"trackers" => $this->getTrackers()->toArray()
		));
	}

}