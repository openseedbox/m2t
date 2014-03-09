<?php

use M2T\Commands\CollectStats;
use Illuminate\Support\Collection;

class CollectStatsTest extends CommandTestCase {

	public function setUp() {
		parent::setUp();

		$this->command = new CollectStats($this->repo, $this->backend);
	}

	public function testOmittingHashParameterLoadsAllTorrents() {
		$this->repo->shouldReceive("all")->once()->andReturn(new Collection());
		$this->repo->shouldReceive("findByHash")->never();

		$this->runCommand($this->command);
	}

	public function testSupplyingHashParemeterChecksMatchingTorrent() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);

		$this->repo->shouldReceive("all")->never();
		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($torrent);
		$this->backend->shouldReceive("getTrackerStats")->once()->with($torrent);
		$this->repo->shouldReceive("persist")->once()->with($torrent);

		$this->runCommand($this->command, array("hash" => $hash));
	}

	public function testOmittingHashParameterChecksAllTorrents() {
		$hashes = array("0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E", "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42A", "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42B");
		$torrents = $this->getMockTorrents($hashes);

		$this->repo->shouldReceive("all")->once()->andReturn($torrents);
		$this->backend->shouldReceive("getTrackerStats")->with($torrents[0]);
		$this->backend->shouldReceive("getTrackerStats")->with($torrents[1]);
		$this->backend->shouldReceive("getTrackerStats")->with($torrents[2]);
		$this->repo->shouldReceive("persist")->with($torrents[0]);
		$this->repo->shouldReceive("persist")->with($torrents[1]);
		$this->repo->shouldReceive("persist")->with($torrents[2]);

		$this->runCommand($this->command);
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage The hash is not valid
	 */
	public function testSupplyingInvalidHashParameterFailsWithError() {
		$this->runCommand($this->command, array("hash" => "badhash"));
	}

}