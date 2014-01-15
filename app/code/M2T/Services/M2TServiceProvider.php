<?php

namespace M2T\Services;

use Illuminate\Support\ServiceProvider;

use M2T\Validation\TorrentHashValidator;
use Vohof\Transmission;

class M2TServiceProvider extends ServiceProvider {

	public function register() {
		$this->registerModels();
		$this->registerCommands();
		$this->registerJobs();		
	}

	public function boot() {
		$this->registerValidator();
	}

	private function registerModels() {
		$this->app->bind("M2T\Models\TorrentRepositoryInterface", "M2T\Models\Eloquent\TorrentRepository");		
	}

	private function registerJobs() {
		$this->app->bind("jobs.add_torrent", "M2T\Jobs\AddTorrentJob");
		$this->app->bind("jobs.collect_stats", "M2T\Jobs\CollectStatsJob");
		$this->app->bind("jobs.monitor_torrent", "M2T\Jobs\MonitorTorrentJob");
	}

	private function registerValidator() {
		$this->app["validator"]->resolver(function($translator, $data, $rules, $messages) {
			return new TorrentHashValidator($translator, $data, $rules, $messages);
		});
	}

	private function registerCommands() {
		$this->app->bind("commands.add_torrent", "M2T\Commands\AddTorrent");
		$this->app->bind("commands.check_torrent", "M2T\Commands\CheckTorrent");
		$this->app->bind("commands.collect_stats", "M2T\Commands\CollectStats");
		$this->app->bind("commands.queue_collect_stats", "M2T\Commands\QueueCollectStats");
	}

}