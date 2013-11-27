<?php

namespace M2T\Util;

class DataHandler {

	public function isUrl($str) {
		return filter_var($str, FILTER_VALIDATE_URL);
	}

	public function isHash($str) {
		return (bool) preg_match('/^[0-9a-f]{40}$/i', $str);
	}

	public function isMagnet($str) {
		$lower = strtolower($str);
		return ($lower === "" || strpos($lower, "magnet:") === 0);
	}

	public function isBase64($str) {
		return (base64_encode(base64_decode($str)) === $str);
	}

}