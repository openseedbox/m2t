<?php

use M2T\Backends\Transmission;

class TransmissionBackendTest extends TestCase {

	public function setUp() {
		parent::setUp();

		$this->transmission = Mockery::mock("Vohof\Transmission");
		$this->backend = Mockery::mock("M2T\Backends\Transmission", array($this->transmission))->makePartial();
	}

	public function testAddTorrentAddsFromMagnetUriIfMagnet() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);
		$torrent->shouldReceive("isFromMagnet")->once()->andReturn(true);
		$torrent->shouldReceive("getMagnetUri")->once()->andReturn("magnet-uri");

		$this->transmission->shouldReceive("add")->with("magnet-uri", false, \Mockery::any()); //second arg means 'encoded', ie is the param base64

		$this->backend->addTorrent($torrent);
	}

	public function testAddTorrentAddsFromBase64IfNotMagnet() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);
		$torrent->shouldReceive("isFromMagnet")->once()->andReturn(false);
		$torrent->shouldReceive("getMagnetUri")->never();
		$torrent->shouldReceive("getBase64Metadata")->andReturn("base64 metadata");

		$this->transmission->shouldReceive("add")->with("base64 metadata", true, \Mockery::any());

		$this->backend->addTorrent($torrent);
	}

	/**
	 * @expectedException M2T\Backends\TorrentNotPresentException
	 * @expectedExceptionMessage Torrent 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E is not present in the backend.
	 */
	public function testExceptionIsThrownIfTorrentNotPresentInIsMetainfoCompleteResponse() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->transmission->shouldReceive("get")->once()->with($hash, \Mockery::any())->andReturn(array("torrents" => array()));

		$this->backend->isMetainfoComplete($torrent);
	}

	public function testTorrentIsNotStoppedIfMetainfoIsNotComplete() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->transmission->shouldReceive("get")->once()->andReturn(array("torrents" => array( 0 => array("metadataPercentComplete" => 0))));

		$this->transmission->shouldReceive("action")->never();

		$this->backend->isMetainfoComplete($torrent);
	}

	public function testTorrentIsStoppedIfMetainfoIsComplete() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->transmission->shouldReceive("get")->once()->andReturn(array("torrents" => array( 0 => array("metadataPercentComplete" => 1))));

		$this->transmission->shouldReceive("action")->once()->with("stop", $hash);

		$this->backend->isMetainfoComplete($torrent);
	}

	/**
	 * @expectedException M2T\Backends\TorrentNotPresentException
	 * @expectedExceptionMessage Torrent 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E is not present in the backend.
	 */
	public function testExceptionIsThrownIfTorrentNotPresentInGetMetainfoAndFilesResponse() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->transmission->shouldReceive("get")->once()->andReturn(array("torrents" => array()));

		$this->backend->getMetainfoAndFiles($torrent);
	}

	public function testTorrentIsPopulatedCorrectlyWhenPassedToGetMetainfoAndFiles() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$torrent->shouldReceive("setBase64Metadata")->once()->with("the metainfo");
		$torrent->shouldReceive("setName")->once()->with("the name");
		$torrent->shouldReceive("setTotalSizeBytes")->once()->with(100);

		$torrent->shouldReceive("clearFiles")->once();

		$file1 = $this->getMockFile("file1", 1000, "dir1/file1");
		$file2 = $this->getMockFile("file2", 500, "file2");

		$torrent->shouldReceive("newFile")->twice()->andReturn($file1, $file2);

		$torrent->shouldReceive("addFile")->with($file1);
		$torrent->shouldReceive("addFile")->with($file2);

		$this->transmission->shouldReceive("get")->once()->andReturn(
			array("torrents" => array( 0 => array(
				"metainfo" => "the metainfo",
				"name" => "the name",
				"totalSize" => 100,
				"files" => array(
					array(
						"name" => "dir1/file1",
						"length" => 1000
					), array(
						"name" => "file2",
						"length" => 500
					)
				)
		))));

		$this->backend->getMetainfoAndFiles($torrent);
	}

	/**
	 * @expectedException M2T\Backends\TorrentNotPresentException
	 * @expectedExceptionMessage Torrent 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E is not present in the backend.
	 */
	public function testExceptionIsThrownIfTorrentNotPresentInGetTrackerStatsResponse() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->transmission->shouldReceive("get")->once()->andReturn(array("torrents" => array()));

		$this->backend->getTrackerStats($torrent);
	}

	public function testTorrentIsPopulatedCorrectlyWhenPassedToGetTrackerStats() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$stats = $this->makeTrackerStats("tracker.com", 10, 5, 20, "Success");

		$this->transmission->shouldReceive("get")->once()->andReturn(
			array("torrents" =>
				array(
					0 => array(
						"trackerStats" => array(
							$stats[0]
						)
					)
				)
			)
		);

		$torrent->shouldReceive("clearTrackers");

		$torrent->shouldReceive("newTracker")->andReturn($stats[1]);
		$torrent->shouldReceive("addTracker")->with($stats[1]);

		$this->backend->getTrackerStats($torrent);
	}

	public function testGetTrackerStatsForMultiplePopulatesTorrentsCorrectly() {
		$hash1 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$hash2 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42A";
		$hash3 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42B";

		$torrents = $this->getMockTorrents(array($hash1, $hash2, $hash3));

		$stats1 = $this->makeTrackerStats("tracker.com", 5, 10, 15,  "Success");
		$stats2 = $this->makeTrackerStats("tracker1.com", 30, 45, 2,  "Some error");
		$stats3 = $this->makeTrackerStats("tracker2.com", 1, 1, 1,  "Success");
		$stats4 = $this->makeTrackerStats("tracker.com", 5, 12, 17,  "Success");

		$torrents[0]->shouldReceive("clearTrackers")->once();
		$torrents[1]->shouldReceive("clearTrackers")->once();
		$torrents[2]->shouldReceive("clearTrackers")->once();

		$torrents[0]->shouldReceive("addTracker")->twice();
		$torrents[1]->shouldReceive("addTracker")->once();
		$torrents[2]->shouldReceive("addTracker")->once();

		$torrents[0]->shouldReceive("newTracker")->twice()->andReturn($stats1[1], $stats2[1]);
		$torrents[1]->shouldReceive("newTracker")->once()->andReturn($stats3[1]);
		$torrents[2]->shouldReceive("newTracker")->once()->andReturn($stats4[1]);

		$this->transmission->shouldReceive("get")->once()->with(array($hash1, $hash2, $hash3), \Mockery::any())
			->andReturn(
				array("torrents" =>
					array(
						0 => array(
							"hashString" => $hash1,
							"trackerStats" => array($stats1[0], $stats2[0])
						),
						1 => array(
							"hashString" => $hash2,
							"trackerStats" => array($stats3[0])
						),
						2 => array(
							"hashString" => $hash3,
							"trackerStats" => array($stats4[0])
						)
					)
				)
		);

		$this->backend->getTrackerStatsForMultiple($torrents);
	}

	public function testGetTrackerStatsForMultipleWithOneTorrentNotInBackendDoesntCauseError() {
		$hash1 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$hash2 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42A";
		$hash3 = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42B";

		$torrents = $this->getMockTorrents(array($hash1, $hash2, $hash3));

		$stats1 = $this->makeTrackerStats("tracker.com", 5, 10, 15,  "Success");
		$stats2 = $this->makeTrackerStats("tracker1.com", 30, 45, 2,  "Some error");
		$stats3 = $this->makeTrackerStats("tracker2.com", 1, 1, 1,  "Success");

		$torrents[0]->shouldReceive("clearTrackers")->once();
		$torrents[1]->shouldReceive("clearTrackers")->once();
		$torrents[2]->shouldReceive("clearTrackers")->never();

		$torrents[0]->shouldReceive("addTracker")->twice();
		$torrents[1]->shouldReceive("addTracker")->once();
		$torrents[2]->shouldReceive("addTracker")->never();

		$torrents[0]->shouldReceive("newTracker")->twice()->andReturn($stats1[1], $stats2[1]);
		$torrents[1]->shouldReceive("newTracker")->once()->andReturn($stats3[1]);
		$torrents[2]->shouldReceive("newTracker")->never();

		$this->transmission->shouldReceive("get")->once()->with(array($hash1, $hash2, $hash3), \Mockery::any())
			->andReturn(
				array("torrents" =>
					array(
						0 => array(
							"hashString" => $hash1,
							"trackerStats" => array($stats1[0], $stats2[0])
						),
						1 => array(
							"hashString" => $hash2,
							"trackerStats" => array($stats3[0])
						)
					)
				)
		);

		$this->backend->getTrackerStatsForMultiple($torrents);
	}

	private function getMockFile($name, $length, $path) {
		$file = \Mockery::mock("M2T\Models\FileInterface");
		$file->shouldReceive("setName")->atLeast()->once()->with($name);
		$file->shouldReceive("setLengthBytes")->atLeast()->once()->with($length);
		$file->shouldReceive("setFullLocation")->atLeast()->once()->with($path);
		return $file;
	}

	private function makeTrackerStats($host = "tracker.com", $seeds = 10, $peers = 5, $complete = 20, $message = "Success") {
		$stats = array("host" => $host, "seederCount" => $seeds, "leecherCount" => $peers, "downloadCount" => $complete, "lastAnnounceResult" => $message);

		$tracker = \Mockery::mock("M2T\Models\TrackerInterface");
		$tracker->shouldReceive("setTrackerUrl")->once()->with($host);
		$tracker->shouldReceive("setSeedCount")->once()->with($seeds);
		$tracker->shouldReceive("setLeecherCount")->once()->with($peers);
		$tracker->shouldReceive("setCompletedCount")->once()->with($complete);
		$tracker->shouldReceive("setMessage")->once()->with($message);

		return array($stats, $tracker);
	}


}
