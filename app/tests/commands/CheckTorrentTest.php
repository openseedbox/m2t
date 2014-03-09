<?php

use M2T\Commands\CheckTorrent;

class CheckTorrentTest extends CommandTestCase {

	use HashParameterTestTrait;

	public function setUp() {
		parent::setUp();

		$this->command = new CheckTorrent($this->repo, $this->backend);
	}

	public function testNoUpdateOccursIfMetainfoNotComplete() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$torrent = $this->getMockTorrent($hash);
		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($torrent);

		$this->backend->shouldReceive("isMetainfoComplete")->once()->andReturn(false);
		$this->backend->shouldReceive("getMetainfoAndFiles")->never();
		$this->repo->shouldReceive("persist")->with($torrent)->never();

		$output = $this->runCommand($this->command, array("hash" => $hash));
		$this->assertEquals("Did not update 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E as metadata is not yet complete", $output[0]);
	}

	public function testCheckValidTorrentSucceeds() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$torrent = $this->getMockTorrent($hash);
		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($torrent);

		$this->backend->shouldReceive("isMetainfoComplete")->once()->andReturn(true);
		$this->backend->shouldReceive("getMetainfoAndFiles")->once();
		$this->repo->shouldReceive("persist")->with($torrent)->once();

		$output = $this->runCommand($this->command, array("hash" => $hash));
		$this->assertEquals("Updated 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E with metainfo and files", $output[0]);
	}

}