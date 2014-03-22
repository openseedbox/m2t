<?php

$prefix = "M2T\Controllers";

Route::get("/api/info/recent", "$prefix\InfoController@getRecent");
Route::get("/api/info/refresh/{hash}", "$prefix\InfoController@getRefresh");
Route::controller("/api/info/{hash?}", "$prefix\InfoController");
Route::controller("/api/upload", "$prefix\UploadController");
Route::get("/api/metadata/{hash}.torrent", array("as" => "metadata.hash", "uses" => "$prefix\MetadataController@getFile"));
Route::controller("/api/metadata/{hash?}", "$prefix\MetadataController");
Route::controller("/", "$prefix\HomeController", array(
	"getIndex" => "index"
));