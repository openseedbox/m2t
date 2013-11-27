<?php

namespace M2T\Models\Eloquent;

use M2T\Models\FileInterface;
use \Eloquent;

class File extends Eloquent implements FileInterface {

	protected $table = "files";

	public function torrent() {
		return $this->belongsTo(get_class(new Torrent()));
	}

	public function getTorrent() {
		return $this->torrent()->get();
	}

}