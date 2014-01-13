<?php

$prefix = "M2T\Controllers";

Route::get("/api/info/recent", "$prefix\InfoController@getRecent");
Route::controller("/api/info/{hash?}", "$prefix\InfoController");
Route::controller("/api/upload/{data?}", "$prefix\UploadController");
Route::get("/api/metadata/{hash}.torrent", array("as" => "metadata.hash", "uses" => "$prefix\MetadataController@getFile"));
Route::controller("/api/metadata/{hash?}", "$prefix\MetadataController");
Route::controller("/", "$prefix\HomeController", array(
	"getIndex" => "index"
));

Route::post('queue/receive', function() {
    return Queue::marshal();
});