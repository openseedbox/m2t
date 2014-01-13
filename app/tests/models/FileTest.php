<?php

use M2T\Models\Eloquent\File;

class FileTest extends PHPUnit_Framework_TestCase {

	public function testToArray() {

		$file = new File();

		$file->setName("test.php");
		$file->setFullLocation("files/test.php");
		$file->setLengthBytes(400);

		$this->assertEquals(array(
			"name" => "test.php",
			"full-location" => "files/test.php",
			"length-bytes" => 400,
			"length-human" => "400 bytes"
		), $file->toArray());
	}

}