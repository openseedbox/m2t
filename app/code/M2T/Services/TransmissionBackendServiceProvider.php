<?php

namespace M2T\Services;

use Illuminate\Support\ServiceProvider;

class TransmissionBackendServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->bind("M2T\Backends\BackendInterface", "M2T\Backends\Transmission");
	}

}