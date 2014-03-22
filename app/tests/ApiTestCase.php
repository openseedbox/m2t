<?php

class ApiTestCase extends TestCase {

	protected function makeRequest($url, array $params = array(), $type = "GET") {
		$response = $this->call($type, $url, $params);
		$this->json_response = json_decode($response->getContent(), true);
		return $this->json_response;
	}

	protected function assertResponseSuccess() {
		$this->assertTrue($this->json_response["success"]);
	}

	protected function assertResponseError($status = 400) {
		$this->assertResponseStatus($status, $this->client->getResponse());
		$this->assertFalse($this->json_response["success"]);
		$this->assertArrayHasKey("message", $this->json_response);
	}

	protected function assertResponseErrorMessage($message) {
		$this->assertEquals($message, $this->json_response["message"]);
	}

}