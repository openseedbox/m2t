<?php

namespace M2T\Services;

use Illuminate\Support\ServiceProvider;

class M2TServiceProvider extends ServiceProvider {

	public function register() {
		$this->registerModels();
	}

	public function registerModels() {
		$this->app->bind("M2T\Models\TorrentRepositoryInterface", "M2T\Models\Eloquent\TorrentRepository");		
	}

}