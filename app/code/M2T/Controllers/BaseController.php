<?php

namespace M2T\Controllers;

use \Controller, \Response;
use Illuminate\Support\ArrayableInterface;

class BaseController extends Controller {

	public function success($data, $status = 200) {
		if ($data instanceof ArrayableInterface) {
			$data = $data->toArray();
		}
		$data = array_merge(array(
			"success" => true
		), $data);
		return Response::json($data, $status);
	}

	public function error($message, $data = array(), $status = 400) {
		$data = array_merge(array(
			"success" => false,
			"message" => $message
		), $data);
		return Response::json($data, $status);
	}

}