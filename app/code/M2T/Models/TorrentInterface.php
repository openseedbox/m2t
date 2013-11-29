<?php

namespace M2T\Models;

interface TorrentInterface {

	public function getInfoHash();

	public function getName();

	public function getTotalSizeBytes();

	public function getBase64Metadata();

	public function getCreateDate();

	public function getTrackers();

	public function clearTrackers();

	public function hasMetadata();

	public function getDownloadLink();

	public function getMagnetUri();

	public function isFromMagnet();

}