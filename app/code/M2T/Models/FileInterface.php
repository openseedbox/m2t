<?php

namespace M2T\Models;

interface FileInterface {

	public function getName();

	public function getFullLocation();

	public function getLengthBytes();

	public function getTorrent();

}