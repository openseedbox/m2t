<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TorrentInterface;
use \Eloquent;

class Torrent extends Eloquent implements TorrentInterface {

	protected $table = "torrents";

	public function files() {
		return $this->hasMany(get_class(new File()));
	}

	public function trackers() {
		return $this->hasMany(get_class(new Tracker()));
	}

}