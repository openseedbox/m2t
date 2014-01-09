<?php

class UploadControllerTest extends ApiTestCase {

	public function tearDown() {
		Mockery::close();
	}

	public function testOmittedDataFails() {
		$response = $this->makeRequest("api/upload");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("Please specify a magnet link, url, hash or base64 data.");
	}

	public function testUnknownDataFails() {
		$response = $this->makeRequestWithData("api/upload/", "07a9de9750158471c3302e4e95edb");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("The supplied data wasnt recognised as a magnet link, url, hash or base64");
		$this->assertArrayHasKey("data", $response);
	}

	public function testCanUploadData() {
		$magnet = "magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980fa6&dn=Pioneer+One+S01E01+720p+x264+VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337";
		$magnet1 = "magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980fa6&dn=Pioneer One S01E01 720p x264 VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337";
		$mock = $this->getMockTorrentRepository();		
		$mock->shouldReceive("add")->once()->with($magnet1)->andReturn($this->getMockTorrent("07a9de9750158471c3302e4e95edb1107f980fa6"));

		$response = $this->makeRequestWithData("api/upload", $magnet);		
		$this->assertResponseOk();
		$this->assertTrue($response["added"]);
		$this->assertEquals("07a9de9750158471c3302e4e95edb1107f980fa6", $response["hash"]);
	}

	protected function makeRequestWithData($url, $data) {
		$uri = $this->app['url']->action("M2T\Controllers\UploadController@getIndex", array("data" => $data));
		return $this->makeRequest($uri);
	}

	private function getMockTorrentRepository() {
		$mock = Mockery::mock("M2T\Models\TorrentRepositoryInterface");
		App::instance("M2T\Models\TorrentRepositoryInterface", $mock);
		return $mock;
	}

	private function getMockTorrent($hash) {
		$mock = Mockery::mock("M2T\Models\TorrentInterface");
		$mock->shouldReceive("getInfoHash")->andReturn($hash);
		return $mock;
	}

}