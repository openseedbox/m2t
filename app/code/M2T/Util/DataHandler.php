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

	public function getDefaultTrackers() {

	}

	/*magnet:?xt=urn:btih:07a9de9750158471c3302e4e95edb1107f980fa6&dn=Pioneer+One+S01E01+720p+x264+VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337*/

}