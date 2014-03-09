<?php

namespace M2T\Models;

use Illuminate\Support\Contracts\ArrayableInterface;

interface FileInterface extends ArrayableInterface {

	/**
	 * Gets the files name.
	 * @return string
	 */
	public function getName();

	/**
	 * Gets the full path to the file, in the context of the torrent directory structure
	 * @return string
	 */
	public function getFullLocation();

	/**
	 * Gets the size of the file, in bytes
	 * @return int
	 */
	public function getLengthBytes();

	/**
	 * Gets the torrent that the file is part of
	 * @return TorrentInterface
	 */
	public function getTorrent();

	/**
	 * Sets the files name
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * Sets the full path to the file in the context of the torrent
	 * @param string $location
	 */
	public function setFullLocation($location);

	/**
	 * Sets the size of the file, in bytes
	 * @param int $length
	 */
	public function setLengthBytes($length);

}