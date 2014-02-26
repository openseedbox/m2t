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

	public function missingMethod($params = array()) {		
		return "No such method: {$params[0]}";
	}

}