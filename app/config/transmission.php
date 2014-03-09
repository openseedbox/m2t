<?php

return array(
	'host' => "http://" . $_ENV['TRANSMISSION_HOST'] . ":" . $_ENV['TRANSMISSION_PORT'],
	'endpoint' => $_ENV["TRANSMISSION_ENDPOINT"]
);