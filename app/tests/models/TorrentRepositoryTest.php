<?php

use Guzzle\Http\Client;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response as GuzzleResponse;

class TorrentRepositoryTest extends TestCase {

	public function setUp() {
		parent::setUp();

		$this->prepareDatabase();
		$this->repo = $this->getRepo();
	}

	public function tearDown() {
		Mockery::close();
	}

	public function testCanAddMagnet() {
		$hash = "07a9de9750158471c3302e4e95edb1107f980fa6";
		$magnet = "magnet:?xt=urn:btih:{$hash}&dn=Pioneer+One+S01E01+720p+x264+VODO&tr=udp%3A%2F%2Ftracker.openbittorrent.com%3A80&tr=udp%3A%2F%2Ftracker.publicbt.com%3A80&tr=udp%3A%2F%2Ftracker.istole.it%3A6969&tr=udp%3A%2F%2Ftracker.ccc.de%3A80&tr=udp%3A%2F%2Fopen.demonii.com%3A1337";
		
		$torrent = $this->repo->add($magnet);
		$this->assertNotNull($torrent);
		$this->assertInstanceOf("M2T\Models\TorrentInterface", $torrent);
		$this->assertEquals($hash, $torrent->getInfoHash());
	}

	public function testCanAddUrl() {
		$url = "http://localhost/test.torrent";
		$this->mockHttpClient(array(
			$url => base64_decode($this->getBase64Metadata())
		));
		$this->repo = $this->getRepo();

		$torrent = $this->repo->add($url);
		$this->assertNotNull($torrent);
		$this->assertInstanceOf("M2T\Models\TorrentInterface", $torrent);
		$this->assertEquals("91c1c0ad9fba72d28a4e91e5ed42e9fae0c03781", $torrent->getInfoHash());
		$this->assertEquals(10059226, $torrent->getTotalSizeBytes());
		$this->assertEquals($this->getBase64Metadata(), $torrent->getBase64Metadata());
		$this->assertEquals("Avicii - Wake Me Up.mp3", $torrent->getName());
		$this->assertCount(5, $torrent->getTrackerUrls());
		$this->assertEquals("udp://tracker.openbittorrent.com:80", $torrent->getTrackerUrls()[0]);
		$this->assertCount(5, $torrent->getTrackers());
		$this->assertEquals("udp://tracker.openbittorrent.com:80", $torrent->getTrackers()->first()->getTrackerUrl());
	}

	public function testCanAddBase64() {
		$metadata = $this->getBase64Metadata();		
		
	}

	public function testCanAddHash() {

	}

	public function testFindByHash() {

	}

	public function testAll() {

	}

	private function mockHttpClient(array $data) {
		$plugin = new MockPlugin();
		foreach ($data as $url => $value) {
			$plugin->addResponse(new GuzzleResponse(200, null, $value));
		}
		$client = new Client();
		$client->addSubscriber($plugin);
		App::instance("Guzzle\Http\Client", $client);
	}

	private function getRepo() {
		return App::make("M2T\Models\TorrentRepositoryInterface");
	}

	private function getBase64Metadata() {
		return "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";
	}

}