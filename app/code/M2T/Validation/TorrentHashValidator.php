<?php

namespace M2T\Validation;

use Illuminate\Validation\Validator;

class TorrentHashValidator extends Validator {	

	public function validateValidHash($attribute, $value, $parameters) {
		return $this->validateRegex($attribute, $value, array('/[a-fA-F0-9]{40}/'));
	}

	public function validateHashInDb($attribute, $value, $parameters) {
		return $this->validateExists($attribute, $value, array("torrents", "hash"));
	} 

}