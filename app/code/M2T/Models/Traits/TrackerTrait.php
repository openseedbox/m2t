<?php

namespace M2T\Models\Traits;

trait TrackerTrait {

	public function toArray() {
		return array(
			"url" => $this->getTrackerUrl(),
			"seeds" => $this->getSeedCount(),
			"leechers" => $this->getLeecherCount(),
			"complete" => $this->getCompletedCount(),
			"message" => $this->getMessage()
		);
	}
	
}