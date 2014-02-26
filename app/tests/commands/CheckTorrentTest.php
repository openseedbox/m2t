<?php

use M2T\Commands\CheckTorrent;

class CheckTorrentTest extends CommandTestCase {

	public function setUp() {
		parent::setUp();

		$this->command = new CheckTorrent($this->repo, $this->backend);
	}

	public function testCheckValidTorrentSucceeds() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$torrent = $this->getMockTorrent($hash);

		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($torrent);

		$output = $this->runCommand($this->command, array("hash" => $hash));


	}

}