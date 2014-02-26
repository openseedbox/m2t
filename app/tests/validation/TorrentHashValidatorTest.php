<?php

use M2T\Validation\TorrentHashValidator;

class TorrentHashValidatorTest extends TestCase {

	public function setUp() {
		parent::setUp();
	}

	public function testValidateHash() {
		$validator = Validator::make(array("torrent_hash" => "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E"), array("torrent_hash" => "valid_hash"));

		$this->assertTrue($validator->passes(), $validator->messages());
	}

	public function testValidateHashFails() {
		$validator = Validator::make(array("torrent_hash" => "not a valid hash"), array("torrent_hash" => "valid_hash"));

		$this->assertTrue($validator->fails());
		$this->assertEquals("The hash is not valid.", $validator->messages()->first());
	}

	public function testHashInDb() {
		$repo = $this->getMockTorrentRepository();
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$repo->shouldReceive("findByHash")->once()->with($hash)->andReturn($this->getMockTorrent($hash));
		$validator = Validator::make(array("torrent_hash" => $hash), array("torrent_hash" => "hash_in_db"));

		$this->assertTrue($validator->passes(), $validator->errors());
	}

	public function testHashInDbFails() {
		$repo = $this->getMockTorrentRepository();
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$repo->shouldReceive("findByHash")->once()->with($hash)->andReturn(false);
		$validator = Validator::make(array("torrent_hash" => $hash), array("torrent_hash" => "hash_in_db"));

		$this->assertTrue($validator->fails(), $validator->errors());
		$this->assertEquals("A matching hash could not be found in the database.", $validator->messages()->first());
	}


}