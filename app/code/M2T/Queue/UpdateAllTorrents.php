<?php

namespace M2T\Queue;

use \Artisan;

class UpdateAllTorrents {

	public function fire($job, $data) {
		Artisan::call("m2t:stats");
		$job->release(30); //wait 30 seconds before re-checking
	}

}