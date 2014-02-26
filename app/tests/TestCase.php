<?php

class TestCase extends Illuminate\Foundation\Testing\TestCase {

	/**
	 * @param M2T\Models\TorrentRepositoryInterface
	 */
	protected $repo = null;

	/**
	 * @param bool
	 */
	protected $mock_repo = true;

	/**
	 * Creates the application.
	 *
	 * @return Symfony\Component\HttpKernel\HttpKernelInterface
	 */
	public function createApplication() {
		$unitTesting = true;

		$testEnvironment = 'testing';

		return require __DIR__.'/../../bootstrap/start.php';
	}

	public function setUp() {
		parent::setUp();

		if ($this->mock_repo) {
			$this->mockTorrentRepository();
		}
	}

	public function tearDown() {
		parent::tearDown();
		Mockery::close();
	}

	protected function prepareDatabase() {
		Artisan::call("migrate");
	}

	protected function mockTorrentRepository() {
		$cn = "M2T\Models\TorrentRepositoryInterface";
		$this->repo = Mockery::mock($cn);
		App::instance($cn, $this->repo);
	}

	protected function getMockTorrents(array $hashes) {
		$ret = array();
		foreach ($hashes as $hash) {
			$mock = Mockery::mock("M2T\Models\TorrentInterface");
			$mock->shouldReceive("getInfoHash")->andReturn($hash);
			$mock->shouldReceive("toArray")->andReturn(array());
			$ret[] = $mock;
		}
		return new Illuminate\Support\Collection($ret);
	}

	protected function getMockTorrent($hash) {
		return $this->getMockTorrents(array($hash))->first();
	}

	protected function getMockTorrentRepository() {
		if (!$this->repo) {
			$this->mockTorrentRepository();
		}
		return $this->repo;
	}

}
