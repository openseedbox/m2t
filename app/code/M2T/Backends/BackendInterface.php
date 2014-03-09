<?php

namespace M2T\Backends;

use M2T\Models\TorrentInterface;
use Illuminate\Support\Collection;

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
	 * @return boolean True if the metainfo is complete, false if it is not
	 *
	 * @throws TorrentNotPresentException if the supplied TorrentInterface isnt actually present in the backend (and needs to be added)
	 */
	public function isMetainfoComplete(TorrentInterface $torrent);

	/**
	 * If the torrent was added from a magnet link and the metadata has been downloaded, we still wont have the base64_metadata and the list of files.
	 * This call retrieves that info, and populates the passed in TorrentInterface with it
	 *
	 * @param TorrentInterface $torrent The torrent
	 * @return TorrentInterface the populated torrent
	 *
	 * @throws TorrentNotPresentException if the supplied TorrentInterface isnt actually present in the backend (and needs to be added)
	 */
	public function getMetainfoAndFiles(TorrentInterface $torrent);

	/**
	 * This call gets the "stats", ie seed/leech/completed count per tracker
	 * The backend should modify the passed in TorrentInterface and populate it with the stats
	 *
	 * @param TorrentInterface $torrent The torrent
	 * @return TorrentInterface the populated torrent
	 *
	 * @throws TorrentNotPresentException if the supplied TorrentInterface isnt actually present in the backend (and needs to be added)
	 */
	public function getTrackerStats(TorrentInterface $torrent);

	/**
	 * This call gets the "stats", ie seed/leech/completed count per tracker
	 * The backend should modify the passed in TorrentInterface and populate it with the stats.
	 * Note: Rather than aborting the entire process, if one of the passes in torrents doesnt exist in the backend, it will simply be skipped.
	 *
	 * @param Illuminate\Support\Collection<TorrentInterface> $torrents The torrents
	 * @return Illuminate\Support\Collection<TorrentInterface>
	 */
	public function getTrackerStatsForMultiple(Collection $torrents);

}

