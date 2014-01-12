<?php

namespace M2T\Models\Traits;

trait FileTrait {
	
	public function toArray() {
		return array(
			"name" => $this->getName(),
			"full-location" => $this->getFullLocation(),
			"length-bytes" => $this->getLengthBytes()
		);
	}

}