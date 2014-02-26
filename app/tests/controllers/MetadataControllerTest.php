<?php

class MetadataControllerTest extends ApiTestCase {

	public function testGetIndexWithNoHashFailsGracefully() {
		$response = $this->makeRequest("api/metadata");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("The hash field is required.");
	}

	public function testGetIndexWithBadHashProducesError() {
		$repo = $this->getMockTorrentRepository();
		$repo->shouldReceive("findByHash")->once()->with("badhash")->andReturn(false);

		$response = $this->makeRequest("api/metadata/badhash");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("The hash is not valid.");
	}

	public function testGetIndexWithGoodButUnknownHashProducesError() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$repo = $this->getMockTorrentRepository();
		$repo->shouldReceive("findByHash")->once()->with($hash)->andReturn(false);

		$response = $this->makeRequest("api/metadata/$hash");
		$this->assertResponseError();
		$this->assertResponseErrorMessage("A matching hash could not be found in the database.");
	}

	public function testGetIndex() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);
		$torrent->shouldReceive("getName")->once()->andReturn("a torrent");
		$torrent->shouldReceive("getBase64Metadata")->once()->andReturn("metadata");

		$repo = $this->getMockTorrentRepository();
		$repo->shouldReceive("findByHash")->twice()->with($hash)->andReturn($torrent);


		$response = $this->makeRequest("/api/metadata/$hash");


		$this->assertResponseSuccess();

		$this->assertArrayHasKey("hash", $response);
		$this->assertArrayHasKey("name", $response);
		$this->assertArrayHasKey("base64_metadata", $response);

		$this->assertEquals($hash, $response["hash"]);
		$this->assertEquals("a torrent", $response["name"]);
		$this->assertEquals("metadata", $response["base64_metadata"]);
	}

	public function testGetFile() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";

		$torrent = $this->getMockTorrent($hash);
		$torrent->shouldReceive("getBase64Metadata")->once()->andReturn("metadata");
		$torrent->shouldReceive("getName")->once()->andReturn("a torrent");

		$repo = $this->getMockTorrentRepository();
		$repo->shouldReceive("findByHash")->twice()->with($hash)->andReturn($torrent);

		$response = $this->call("GET", "/api/metadata/{$hash}.torrent");

		$this->assertEquals("application/x-bittorrent", $response->headers->get("Content-Type"), $response);
		$this->assertEquals("attachment; filename=a+torrent.torrent", $response->headers->get("Content-Disposition"), $response);

	}

}