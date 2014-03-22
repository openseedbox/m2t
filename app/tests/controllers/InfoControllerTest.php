<?php

class InfoControllerTest extends ApiTestCase {

	public function testGetIndexWithBadHashReturnsError() {
		$this->repo->shouldReceive("findByHash")->once()->with("bad_hash")->once()->andReturn(null);

		$response = $this->makeRequest("api/info/bad_hash");

		$this->assertResponseError();
		$this->assertResponseErrorMessage("The hash is not valid.");
	}

	public function testGetIndex() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";

		$this->repo->shouldReceive("findByHash")->twice()->with($hash)->andReturn($this->getMockTorrent($hash));

		$response = $this->makeRequest("api/info/$hash");

		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertArrayHasKey("torrent", $response);
	}

	public function testGetRecent() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";
		$hash1 = "07a9de9750158471c3302e4e95edb1107f980fa7";
		$hash2 = "07a9de9750158471c3302e4e95edb1107f980fa8";

		$this->repo->shouldReceive("getRecent")->once()->andReturn($this->getMockTorrents(array($hash, $hash1, $hash2)));
		$response = $this->makeRequest("api/info/recent");

		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertArrayHasKey("torrents", $response);
		$this->assertCount(3, $response["torrents"]);
	}

	public function testGetRefresh() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";
		\Artisan::shouldReceive("call")->with("m2t:check", array("hash" => $hash));
		\Artisan::shouldReceive("call")->with("m2t:stats", array("hash" => $hash));

		$response = $this->makeRequest("api/info/refresh/$hash");
		$this->assertResponseOk();
		$this->assertResponseSuccess();
		$this->assertArrayHasKey("refreshed", $response);
		$this->assertEquals($hash, $response["refreshed"]);
	}

}