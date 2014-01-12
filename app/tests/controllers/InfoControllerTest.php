<?php

class InfoControllerTest extends ApiTestCase {

	public function setUp() {
		parent::setUp();

		$this->mockTorrentRepository();
	}

	public function tearDown() {
		Mockery::close();
	}

	public function testGetIndex() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";

		$this->repo->shouldReceive("findByHash")->once()->andReturn($this->mockTorrent($hash));

		$result = Mockery::mock("stdClass");
		$result->shouldReceive("fails")->andReturn(false);
		\Validator::shouldReceive("make")->andReturn($result);

		$response = $this->makeRequest("api/info/$hash");
		
		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertArrayHasKey("torrent", $response);
	}

	public function testGetIndexWithBadHashReturnsError() {
		$result = Mockery::mock("stdClass");
		$result->shouldReceive("fails")->andReturn(false);
		\Validator::shouldReceive("make")->andReturn($result);

		$this->repo->shouldReceive("findByHash")->with("bad_hash")->once()->andReturn(null);

		$response = $this->makeRequest("api/info/bad_hash");

		$this->assertResponseError();
		$this->assertResponseErrorMessage("Hash empty or invalid: bad_hash");
	}

	public function testGetRecent() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";
		$hash1 = "07a9de9750158471c3302e4e95edb1107f980fa7";
		$hash2 = "07a9de9750158471c3302e4e95edb1107f980fa8";

		$this->repo->shouldReceive("getRecent")->once()->andReturn($this->mockTorrents(array($hash, $hash1, $hash2)));
		$response = $this->makeRequest("api/info/recent");

		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertArrayHasKey("torrents", $response);
		$this->assertCount(3, $response["torrents"]);
	}

	private function mockTorrentRepository() {
		$cn = "M2T\Models\TorrentRepositoryInterface";
		$this->repo = Mockery::mock($cn);
		App::instance($cn, $this->repo);
	}

	private function mockTorrents(array $hashes) {
		$ret = array();
		foreach ($hashes as $hash) {
			$mock = Mockery::mock("M2T\Models\TorrentInterface");
			$mock->shouldReceive("getInfoHash")->andReturn($hash);
			$mock->shouldReceive("toArray")->andReturn(array());	
			$ret[] = $mock;
		}
		return new Illuminate\Support\Collection($ret);
	}

	private function mockTorrent($hash) {
		return $this->mockTorrents(array($hash))->first();
	}

}