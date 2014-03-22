<?php

class UploadControllerTest extends ApiTestCase {

	public function testOmittedDataFails() {
		$response = $this->makeRequest("/api/upload");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("Please specify a magnet link, url, hash or base64 data.");
	}

	public function testUnknownDataFails() {
		$repo = $this->getMockTorrentRepository();

		$hash = "07a9de9750158471c3302e4e95edb";
		$repo->shouldReceive("add")->once()->with($hash);

		$response = $this->makeRequestWithData("/api/upload", $hash);
		$this->assertResponseError();
		$this->assertResponseErrorMessage("The supplied data wasnt recognised as a magnet link, url, hash or base64");
		$this->assertArrayHasKey("data", $response);
	}

	public function testCanUploadData() {
		$magnet = "magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980fa6&dn=Pioneer+One+S01E01+720p+x264+VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337";
		$mock = $this->getMockTorrentRepository();
		$mock->shouldReceive("add")->once()->with($magnet)->andReturn($this->getMockTorrent("07a9de9750158471c3302e4e95edb1107f980fa6"));
		Queue::shouldReceive("connected")->once();
		Queue::shouldReceive("push")->once();

		$response = $this->makeRequestWithData("api/upload", $magnet);
		$this->assertResponseOk();
		$this->assertTrue($response["added"]);
		$this->assertEquals("07a9de9750158471c3302e4e95edb1107f980fa6", $response["hash"]);
	}

	protected function makeRequestWithData($url, $data) {
		$uri = $this->app['url']->action("M2T\Controllers\UploadController@getIndex");
		return $this->makeRequest($uri, array("url" => $data));
	}

}