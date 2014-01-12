<?php

namespace M2T\Models;

interface TorrentRepositoryInterface {

	/**
	 * Create a new torrent
	 * @param $data A magnet, URL or info_hash
	 * @return M2T\Models\TorrentInterface The added torrent
	 */
	public function add($data);

	/**
	 * Return a list of all the torrents in the system
	 * @return Illuminate\Support\Collection<M2T\Models\TorrentInterface>
	 */
	public function all();		

	/**
	 * Find a torrent by its info_hash
	 * @param string $hash The SHA-1 info_hash
	 * @return M2T\Models\TorrentInterface The torrent
	 */
	public function findByHash($hash);

	/**
	 * Gets the last $limit torrents added, newest first
	 * @param int $limit How many results to return
	 * @return Illuminate\Support\Collection<M2T\Models\TorrentInterface>
	 */
	public function getRecent($limit = 10);
	
}