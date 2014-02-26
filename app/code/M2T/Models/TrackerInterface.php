<?php

namespace M2T\Models;

use Illuminate\Support\Contracts\ArrayableInterface;

interface TrackerInterface extends ArrayableInterface {

	/**
	 * Get the torrent that this Tracker belongs to
	 * @return M2T\Models\TorrentInterface
	 */
	public function getTorrent();

	/**
	 * Gets the URL of this tracker
	 * @return string
	 */
	public function getTrackerUrl();

	/**
	 * Gets the number of seeders that this tracker is reporting for the torrent
	 * @return int
	 */
	public function getSeedCount();

	/**
	 * Gets the number of leechers that this tracker is reporting for the torrent
	 * @return int
	 */
	public function getLeecherCount();

	/**
	 * Gets the number of completions that this tracker is reporting for the torrent
	 * @return int
	 */
	public function getCompletedCount();

	/**
	 * Gets the message that a tracker might display (typically this is an error)
	 * @return string
	 */
	public function getMessage();

	/**
	 * Sets the tracker URL
	 * @param string $tracker_url
	 */
	public function setTrackerUrl($tracker_url);

	/**
	 * Sets the tracker seed count
	 * @param int $seed_count
	 */
	public function setSeedCount($seed_count);

	/**
	 * Sets the tracker leecher count
	 * @param int $leecher_count
	 */
	public function setLeecherCount($leecher_count);

	/**
	 * Sets the completed count
	 * @param int $completed_count
	 */
	public function setCompletedCount($completed_count);

	/**
	 * Sets the tracker message
	 * @param string $message
	 */
	public function setMessage($message);

}
