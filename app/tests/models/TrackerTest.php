<?php

use M2T\Models\Eloquent\Tracker;

class TrackerTest extends PHPUnit_Framework_TestCase {

	public function testToArray() {
		$tracker = new Tracker();

		$tracker->setTrackerUrl("http://test");
		$tracker->setSeedCount(10);
		$tracker->setLeecherCount(5);
		$tracker->setCompletedCount(1);
		$tracker->setMessage("success");

		$this->assertEquals(array(
			"url" => "http://test",
			"seeds" => 10,
			"leechers" => 5,
			"complete" => 1,
			"message" => "success"
		), $tracker->toArray());
	}

}