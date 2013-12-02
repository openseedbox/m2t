<?php

namespace M2T\Models;

interface TorrentRepositoryInterface {

	public function findByHash($hash);

	public function add($data);

	public function all();
	
}