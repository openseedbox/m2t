<?php

namespace M2T\Models;

interface TorrentRepositoryInterface {

	public function findByHash($hash);

	public function add($data);

	public function addFromMagnet($magnet);

	public function addFromHash($hash);

	public function addFromUrl($url);

	public function addFromBase64($base64);
	
}