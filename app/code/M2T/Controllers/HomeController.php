<?php

namespace M2T\Controllers;

use \Controller, \View, \Redirect;

class HomeController extends Controller {

	public function getIndex() {
		return View::make("home.index");
	}

	public function getApi() {
		return Redirect::route("index");
	}

}