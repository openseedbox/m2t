<?php

namespace M2T\Jobs;

use Illuminate\Foundation\Artisan;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

use \App;

class BaseJob {

	protected function getInfoHash($data) {
		return $data["hash"];
	}

	public function call($command, array $arguments = array()) {
		$app = new Artisan(App::make("app"));//->getArtisan();
		 $instance = $app->find($command);

		 $arguments['command'] = $command;

		 return $instance->run(new ArrayInput($arguments), new ConsoleOutput);
	}	

}