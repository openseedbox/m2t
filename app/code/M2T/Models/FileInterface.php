<?php

namespace M2T\Models;

use Illuminate\Support\Contracts\ArrayableInterface;

interface FileInterface extends ArrayableInterface {

	public function getName();

	public function getFullLocation();

	public function getLengthBytes();

	public function getTorrent();

	public function setName($name);

	public function setFullLocation($location);

	public function setLengthBytes($length);	

}