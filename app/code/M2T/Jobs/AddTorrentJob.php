<?php

namespace M2T\Jobs;

use \Log;

class AddTorrentJob extends BaseJob {

	public function fire($job, $data) {
		Log::info("fired for {$this->getInfoHash($data)}");
		$this->call("m2t:add", array($this->getInfoHash($data)));
		$job->delete();
	}

}