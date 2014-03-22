<?php

use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Output\Output;
use Illuminate\Console\Command;
use Illuminate\Console\Application as ConsoleApplication;

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

}