<?php

use M2T\Models\Eloquent\Torrent;
use Illuminate\Support\Collection;

class TorrentTest extends PHPUnit_Framework_TestCase {	

	public function testToArray() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";

		$torrent = new Torrent();
		$torrent->setInfoHash($hash);
		$torrent->setName("Test");		

		$a = $torrent->toArray();

		$this->assertArrayHasKey("hash", $a);
		$this->assertArrayHasKey("has-metadata", $a);
		$this->assertArrayHasKey("name", $a);

		$this->assertEquals(array("hash" => $hash, "has-metadata" => false, "name" => "Test"), $a);
	}

	public function testToArrayWithMetadata() {

		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";

		$torrent = Mockery::mock("M2T\Models\Eloquent\Torrent[getFiles,getTrackers]");
		$torrent->shouldReceive("getFiles")->once()->andReturn(new Collection());
		$torrent->shouldReceive("getTrackers")->once()->andReturn(new Collection());

		$torrent->setInfoHash($hash);
		$torrent->setName("Test");
		$torrent->setBase64Metadata("testmetadata");
		$torrent->setTotalSizeBytes(400);		

		$a = $torrent->toArray();		

		$this->assertArrayHasKey("download-link", $a);
		$this->assertArrayHasKey("total-size-bytes", $a);
		$this->assertArrayHasKey("files", $a);
		$this->assertArrayHasKey("trackers", $a);

		$this->assertEquals(array(
			"hash" => $hash,
			"has-metadata" => true,
			"name" => "Test",
			"download-link" => "to implement",
			"total-size-bytes" => 400,
			"files" => array(),
			"trackers" => array()
		), $a);

	}
	
}