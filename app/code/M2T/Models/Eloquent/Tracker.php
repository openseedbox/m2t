<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TrackerInterface;
use M2T\Models\Traits\TrackerTrait;
use \Eloquent;

class Tracker extends Eloquent implements TrackerInterface {

	use TrackerTrait;

	protected $table = "trackers";

	protected $fillable = array("torrent_id", "tracker_url", "seeds", "leechers", "completed", "message");

	protected $hidden = array("torrent_id", "id");

	protected $touches = array("torrent");

	public $timestamps = false;

	protected function torrent() {
		return $this->belongsTo("M2T\Models\Eloquent\Torrent");
	}

	public function getTorrent() {
		return $this->torrent();
	}

	public function getTrackerUrl() {
		return $this->tracker_url;
	}

	public function getSeedCount() {
		return $this->seeds ?: 0;
	}

	public function getLeecherCount() {
		return $this->leechers ?: 0;
	}

	public function getCompletedCount() {
		return $this->completed ?: 0;
	}

	public function getMessage() {
		return $this->message;
	}

	public function setTrackerUrl($tracker_url) {
		$this->tracker_url = $tracker_url;
	}

	public function setSeedCount($seed_count) {
		$this->seeds = $seed_count;
	}

	public function setLeecherCount($leecher_count) {
		$this->leechers = $leecher_count;
	}

	public function setCompletedCount($completed_count) {
		$this->completed = $completed_count;
	}

	public function setMessage($message) {
		$this->message = $message;
	}

}