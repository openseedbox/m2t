<?php

use M2T\Commands\AddTorrent;

class AddTorrentTest extends CommandTestCase {

	use HashParameterTestTrait;

	public function setUp() {
		parent::setUp();

		$this->command = new AddTorrent($this->repo, $this->backend);
	}

	public function testAddValidTorrentSucceeds() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$torrent = $this->getMockTorrent($hash);

		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($torrent);
		$this->backend->shouldReceive("addTorrent")->once()->with($torrent);

		$output = $this->runCommand($this->command, array("hash" => $hash));

		$this->assertEquals("Adding torrent with hash: 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E", $output[0]);
	}

}