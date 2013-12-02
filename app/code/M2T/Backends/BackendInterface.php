<?php

namespace M2T\Backends;

use M2T\Models\TorrentInterface;

interface BackendInterface {

	/**
	 * Adds a torrent to the backend.
	 * 
	 * If the torrent already exists in the backend, this method should just return as though it was added.
	 * 
	 * @param TorrentInterface $torrent The torrent
	 */
	public function addTorrent(TorrentInterface $torrent);

	/**
	 * If the torrent was added from a magnet link, there wont be any metainfo until the actual torrent data is downloaded
	 * This method indicates that the metainfo is available
	 * 
	 * @param TorrentInterface $torrent The torrent 
	 */
	public function isMetainfoComplete(TorrentInterface $torrent);

	/**
	 * If the torrent was added from a magnet link and the metadata has been downloaded, we still wont have the base64_metadata and the list of files.
	 * This call retrieves that info.
	 * 
	 * @param TorrentInterface $torrent The torrent
	 */
	public function getMetainfoAndFiles(TorrentInterface $torrent);

	/**
	 * This call gets the "stats", ie seed/leech/completed count per tracker
	 * 
	 * @param TorrentInterface $torrent The torrent
	 */
	public function getTrackerStats(TorrentInterface $torrent);

}

