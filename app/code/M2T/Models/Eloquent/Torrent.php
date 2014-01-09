<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TorrentInterface;
use M2T\Models\FileInterface;
use M2T\Models\TrackerInterface;
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

	public function setName($name) {
		$this->name = $name;
	}

	public function setTotalSizeBytes($size) {
		$this->total_size_bytes = $size;
	}

	public function setBase64Metadata($metadata) {
		$this->base64_metadata = $metadata;
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

	public function getTrackerUrls() {
		return $this->trackers()->get()->map(function($tracker) {
			return $tracker->getTrackerUrl();
		})->toArray();
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

	public function newFile() {
		return new File(array(
			"torrent_id" => $this->id
		));
	}

	public function addFile(FileInterface $file) {
		File::create(array(
			"name" => $file->getName(),
			"full_location" => $file->getFullLocation(),
			"length_bytes" => $file->getLengthBytes(),
			"torrent_id" => $this->id
		));
	}

	public function newTracker() {
		return new Tracker();
	}

	public function addTracker(TrackerInterface $tracker) {
		Tracker::create(array(
			"tracker_url" => $tracker->getTrackerUrl(),
			"seeds" => $tracker->getSeedCount(),
			"leeches" => $tracker->getLeecherCount(),
			"completed" => $tracker->getCompletedCount(),
			"message" => $tracker->getMessage(),
			"torrent_id" => $this->id
		));
	}

	public function clearFiles() {
		$files = $this->getFiles();
		DB::transaction(function() use ($files) {
			foreach ($files as $file) {
				$file->delete();
			}
		});
	}

	public function toArray() {
		$data = array(
			"has_metadata" => $this->hasMetadata(),
			"hash" => $this->getInfoHash(),
			"name" => $this->getName()			
		);
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