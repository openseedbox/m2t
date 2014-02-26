<?php

class ControllerTest extends TestCase {

	public function testMissingMethod() {
		$response = $this->call("GET", "/missing");

		$this->assertEquals("No such method: missing", $response->getContent());
	}

	public function testApiMissingMethod() {
		$response = $this->call("GET", "/api/info/missing/method");

		$this->assertEquals("application/json", $response->headers->get("Content-Type"));
		$this->assertEquals(array("success" => false, "message" => "No such method."), $response->getData(true));
	}

}