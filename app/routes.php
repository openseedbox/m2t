<?php

$prefix = "M2T\Controllers";

Route::controller("/api/info/{hash?}", "$prefix\InfoController");
Route::controller("/api/upload/{data?}", "$prefix\UploadController");
Route::controller("/api/metadata/{hash?}", "$prefix\MetadataController");
Route::controller("/", "$prefix\HomeController", array(
	"getIndex" => "index"
));