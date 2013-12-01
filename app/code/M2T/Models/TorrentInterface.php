<?php

namespace M2T\Models;

interface TorrentInterface {

	public function getInfoHash();

	public function getName();

	public function getTotalSizeBytes();

	public function getBase64Metadata();

	public function setName($name);

	public function setTotalSizeBytes($size);

	public function setBase64Metadata($metadata);	

	public function getCreateDate();

	public function getTrackers();

	public function clearTrackers();

	public function hasMetadata();

	public function getDownloadLink();

	public function getMagnetUri();

	public function isFromMagnet();

	public function newFile();

	public function addFile(FileInterface $file);

	public function newTracker();

	public function addTracker(TrackerInterface $tracker);

	public function getFiles();

	public function clearFiles();

}