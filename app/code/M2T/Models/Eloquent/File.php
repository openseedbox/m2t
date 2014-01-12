<?php

namespace M2T\Models\Eloquent;

use M2T\Models\FileInterface;
use M2T\Models\Traits\FileTrait;
use \Eloquent;

class File extends Eloquent implements FileInterface {

	use FileTrait;

	protected $table = "files";

	protected $fillable = array("torrent_id", "name", "full_location", "length_bytes");

	protected $hidden = array("torrent_id", "id");

	public $timestamps = false;

	protected function torrent() {
		return $this->belongsTo(get_class(new Torrent()));
	}

	public function getTorrent() {
		return $this->torrent()->get();
	}

	public function getName() {
		return $this->name;
	}

	public function getFullLocation() {
		return $this->full_location;
	}

	public function getLengthBytes() {
		return $this->length_bytes;
	}

	public function setName($name) {
		$this->name = $name;
	}

	public function setFullLocation($location) {
		$this->full_location = $location;
	}

	public function setLengthBytes($length) {
		$this->length_bytes = $length;
	}

}