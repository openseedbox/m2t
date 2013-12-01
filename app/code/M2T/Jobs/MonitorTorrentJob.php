<?php

namespace M2T\Jobs;

use \Artisan;

class MonitorTorrentJob extends BaseJob {

	public function fire($job, $data) {
		$this->call("m2t:check", array($this->getInfoHash($data)));
		$job->delete();
	}

}