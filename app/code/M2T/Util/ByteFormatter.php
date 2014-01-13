<?php

namespace M2T\Util;

class ByteFormatter {	

	/**
	 * Format the supplied bytes into a human readable filesize
	 * @param int $bytes The number of bytes
	 * @return string The human-readable version
	 */
	public static function format($bytes) {
		$kilo = 1024;
		$mega = $kilo * 1024;
		$giga = $mega * 1024;
		$tera = $giga * 1024;

		if ($bytes < $kilo) {
		    return $bytes . ' bytes';
		}
		if ($bytes < $mega) {
		    return round($bytes / $kilo, 2) . ' KB';
		}
		if ($bytes < $giga) {
		    return round($bytes / $mega, 2) . ' MB';
		}
		if ($bytes < $tera) {
		    return round($bytes / $giga, 2) . ' GB';
		}
		return round($bytes / $tera, 2) . ' TB';
	}

}