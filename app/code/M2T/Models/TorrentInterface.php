<?php

namespace M2T\Models;

use Openseedbox\Parser\TorrentInterface as ParserTorrentInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

interface TorrentInterface extends ParserTorrentInterface, ArrayableInterface {

	public function setInfoHash($hash);

	public function setName($name);

	public function setTotalSizeBytes($size);

	public function setBase64Metadata($metadata);	

	public function getCreateDate();

	public function getTrackers();

	public function clearTrackers();

	public function hasMetadata();	

	public function newFile();

	public function addFile(FileInterface $file);

	public function newTracker();

	public function addTracker(TrackerInterface $tracker);

	public function getFiles();

	public function clearFiles();

}