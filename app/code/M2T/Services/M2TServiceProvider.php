<?php

namespace M2T\Services;

use Illuminate\Support\ServiceProvider;

use M2T\Validation\TorrentHashValidator;
use Vohof\Transmission;

class M2TServiceProvider extends ServiceProvider {

	public function register() {
		$this->registerModels();
		$this->registerJobs();
		$this->registerTransmission();
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

	private function registerTransmission() {
		$this->app['transmission'] = $this->app->share(function($app) {
            return new Transmission($app['config']->get('transmission'));
        });
	}

}