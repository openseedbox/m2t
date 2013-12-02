<?php

use Illuminate\Support\ServiceProvider;

class TransmissionBackendServiceProvider extends ServiceProvider {

	public function register() {
		$this->app->bind("M2T\Backend\BackendInterface", "M2T\Backend\Transmission");
	}

}