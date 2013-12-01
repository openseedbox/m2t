<?php

namespace M2T\Controllers;

use \Controller, \Response, \App, \Exception, \Validator as ValidatorFacade;
use Illuminate\Support\ArrayableInterface;
use Illuminate\Validation\Validator;

class BaseController extends Controller {

	protected $torrents;

	public function __construct() {
		$self = $this;

		if (App::environment() == "production") {
			$this->beforeFilter(function() use ($self) {
				App::error(function(Exception $ex) use ($self) {
					return $self->error($ex->getMessage());
				});
			});
		}

		$this->torrents = App::make("M2T\Models\TorrentRepositoryInterface");
	}

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
		if ($message instanceof Validator) {
			$data["errors"] = $message->messages()->toArray();
			$message = $message->messages()->first();
		}
		$data = array_merge(array(
			"success" => false,
			"message" => $message
		), $data);
		return Response::json($data, $status);
	}

	public function missingMethod($params) {
		return $this->error("No such method.");
	}

	protected function getTorrent($hash) {
		return $this->torrents->findByHash($hash);
	}

	protected function getValidator($hash) {
		return ValidatorFacade::make(array(
				"hash" => $hash
			), array(
				"hash" => "required|valid_hash|hash_in_db"
		));
	}	

}