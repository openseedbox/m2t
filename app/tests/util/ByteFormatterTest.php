<?php

use M2T\Util\ByteFormatter;

class ByteFormatterTest extends PHPUnit_Framework_TestCase {

	public function setUp() {
		$this->formatter = new ByteFormatter();
	}

	public function testFormatBytes() {		
		$this->assertEquals("400 bytes", $this->formatter->format(400));
		$this->assertNotEquals("1025 bytes", $this->formatter->format(1025));
	}

	public function testFormatKB() {
		$this->assertEquals("1.2 KB", $this->formatter->format(1224));
		$this->assertEquals("1.21 KB", $this->formatter->format(1244));
		$this->assertNotEquals("1025 KB", $this->formatter->format(1048577));
	}

	public function testFormatMB() {
		$this->assertEquals("1 MB", $this->formatter->format(1048577));
	}

	public function testFormatGB() {
		$this->assertEquals("1 GB", $this->formatter->format(1073741824));
	}

	public function testFormatTB() {
		$this->assertEquals("1 TB", $this->formatter->format(1024 * 1024 * 1024 * 1024));
	}

}