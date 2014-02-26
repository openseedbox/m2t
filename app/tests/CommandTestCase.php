<?php

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\Output;
use Illuminate\Console\Command;

class CommandTestCase extends TestCase {

	public function setUp() {
		parent::setUp();

		$this->backend = Mockery::mock("M2T\Backends\BackendInterface");
	}

	protected function runCommand(Command $command, array $args = array()) {
		$output = $this->makeOutputStream();
		$command->run($this->makeArguments($args), $output);
		return $this->getStreamOutput($output);
	}

	protected function makeArguments(array $args) {
		return new ArrayInput($args);
	}

	protected function makeOutputStream() {
		$resource = fopen("php://memory", "r+");
		return new StreamOutput($resource);
	}

	protected function getStreamOutput(Output $output) {
		$stream = $output->getStream();
		rewind($stream);
		$contents = stream_get_contents($stream);
		fclose($stream);
		return explode("\n", trim($contents));
	}

	/* Run these tests on each command to make sure that the 'hash' parameter is being handled correctly */

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