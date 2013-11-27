<?php

namespace M2T\Models;

interface TorrentInterface {

	public function getHash();

	public function getName();

	public function getTotalSizeBytes();

	public function getBase64Metadata();

	public function getCreateDate();

}