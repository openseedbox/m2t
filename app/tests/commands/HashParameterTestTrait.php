<?php

trait HashParameterTestTrait {

	/* Run these tests on each command to make sure that the 'hash' parameter is being handled correctly (as each command takes a hash parameter) */

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage Not enough arguments
	 */
	public function testCommandFailsWithNoHashParameter() {
		$this->runCommand($this->command);
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage The hash field is required
	 */
	public function testCommandFailsWithEmptyHashParameter() {
		$this->runCommand($this->command, array("hash" => ""));
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage The hash is not valid
	 */
	public function testCommandFailsWithBadHashParameter() {
		$this->runCommand($this->command, array("hash" => "badhash"));
	}

	/**
	 * @expectedException RuntimeException
	 * @expectedExceptionMessage No such torrent with hash: 0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E
	 */
	public function testCommandFailsWithUnknownHashParameter() {
		$hash = "0001DBD9F8763E969E71BF2E6CB9424EB0CEA42E";
		$this->repo->shouldReceive("findByHash")->once()->with($hash)->andReturn(null);
		$this->runCommand($this->command, array("hash" => $hash));
	}

}