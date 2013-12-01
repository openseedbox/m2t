<?php

namespace M2T\Models\Eloquent;

use M2T\Models\TrackerInterface;
use \Eloquent;

class Tracker extends Eloquent implements TrackerInterface {

	protected $table = "trackers";

	protected $fillable = array("torrent_id", "tracker_url", "seeds", "leechers", "completed", "message");

	protected $hidden = array("torrent_id", "id");

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