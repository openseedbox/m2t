<?php

namespace M2T\Models;

interface TrackerInterface {

	public function getTorrent();

	public function getTrackerUrl();

	public function getSeedCount();

	public function getLeecherCount();

	public function getCompletedCount();

}
