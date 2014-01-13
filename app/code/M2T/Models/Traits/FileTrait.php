<?php

namespace M2T\Models\Traits;

use M2T\Util\ByteFormatter;

trait FileTrait {
	
	public function toArray() {
		$formatter = new ByteFormatter();
		return array(
			"name" => $this->getName(),
			"full-location" => $this->getFullLocation(),
			"length-bytes" => $this->getLengthBytes(),
			"length-human" => $formatter->format($this->getLengthBytes())
		);
	}

}