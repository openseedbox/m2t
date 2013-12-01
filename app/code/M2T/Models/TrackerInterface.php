<?php

namespace M2T\Models;

interface TrackerInterface {

	public function getTorrent();

	public function getTrackerUrl();

	public function getSeedCount();

	public function getLeecherCount();

	public function getCompletedCount();

	public function getMessage();

	public function setTrackerUrl($tracker_url);

	public function setSeedCount($seed_count);

	public function setLeecherCount($leecher_count);

	public function setCompletedCount($completed_count);

	public function setMessage($message);

}
