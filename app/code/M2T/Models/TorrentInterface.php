<?php

namespace M2T\Models;

use Openseedbox\Parser\TorrentInterface as ParserTorrentInterface;
use Illuminate\Support\Contracts\ArrayableInterface;

interface TorrentInterface extends ParserTorrentInterface, ArrayableInterface {

	/**
	 * Set the torrents info_hash
	 * @param string $hash The SHA-1 info_hash
	 */
	public function setInfoHash($hash);

	/**
	 * Set the torrents name
	 * @param string $name
	 */
	public function setName($name);

	/**
	 * Sets the total size of the torrent (in bytes)
	 * @param int $size
	 */
	public function setTotalSizeBytes($size);

	/**
	 * Sets the torrents Base64 metadata. This is the torrent file, encoded as base64
	 * @param string $metadata
	 */
	public function setBase64Metadata($metadata);

	/**
	 * Returns the date the torrent was added to the system
	 * @return Carbon\Carbon The date
	 */
	public function getCreateDate();

	/**
	 * Returns the list of trackers for the torrent
	 * @return Illuminate\Support\Collection<M2T\Models\TrackerInterface> The list
	 */
	public function getTrackers();

	/**
	 * Clears the list of trackers for the torrent
	 * After this is run, getTrackers() should return an empty collection
	 */
	public function clearTrackers();

	/**
	 * Indicates whether or not the base64_metadata is populated
	 * @return boolean
	 */
	public function hasMetadata();

	/**
	 * Returns a new FileInterface instance bound to this torrent
	 * This is so the correct implementation can be returned for the torrents implementation
	 * @return M2T\Models\FileInterface
	 */
	public function newFile();

	/**
	 * Adds a new file to this torrent. The file should have been created with newFile() for compatibility.
	 * @param M2T\Models\FileInterface $file
	 */
	public function addFile(FileInterface $file);

	/**
	 * Returns a new Tracker instance bound to this torrent
	 * This is so the correct implementation can be returned for the torrents implementation
	 * @return M2T\Models\Tracker
	 */
	public function newTracker();

	/**
	 * Adds a new tracker to this torrent. The tracker should have been created with newTracker() for compatibility.
	 * @param M2T\Models\FileInterface $tracker
	 */
	public function addTracker(TrackerInterface $tracker);

	/**
	 * Returns all the files for this torrent
	 * @return Illuminate\Support\Collection<M2T\Models\FileInterface>
	 */
	public function getFiles();

	/**
	 * Clears all the files on this torrent. After this is run, getFiles() should return an empty collection.
	 */
	public function clearFiles();

	/**
	 * Returns an absolute download link for the torrent. A user should be able to make a GET request to this link to retrieve the torrent.
	 * @return string
	 */
	public function getDownloadLink();

	/**
	 * Returns a timestamp of when the torrent was last updated. Should be in the format Y-m-d H:i, eg 2013-01-16 14:23
	 * @return string
	 */
	public function getLastUpdated();

}