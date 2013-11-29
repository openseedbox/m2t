<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TrackerInterface;
use \Eloquent;

class Tracker extends Eloquent implements TrackerInterface {

	protected $table = "trackers";

	protected $fillable = array("torrent_id", "tracker_url", "seeds", "leechers", "completed");

	public $timestamps = false;

	public function torrent() {
		return $this->belongsTo(get_class("Torrent"));
	}

	public function getTorrent() {
		return $this->torrent();
	}

	public function getTrackerUrl() {
		return $this->tracker_url;
	}

	public function getSeedCount() {
		return $this->seeds;
	}

	public function getLeecherCount() {
		return $this->leechers;
	}

	public function getCompletedCount() {
		return $this->completed;
	}

}