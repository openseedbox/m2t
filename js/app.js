(function() {

	var app = angular.module("m2t", ["ngRoute"]);

	var api_base = window.API_BASE; //loaded from js/api_location.js

	app.config(["$routeProvider", "$sceProvider", function($routeProvider, $sceProvider) {
		
		$routeProvider.when("/docs", {
			"templateUrl" : "templates/docs.html",
			"controller" : "DocsController"
		}).when("/", {
			"templateUrl" : "templates/main.html",
			"controller" : "MainController"
		}).when("/:hash", {
			"templateUrl" : "templates/torrent.html",
			"controller" : "TorrentController"
		});

		$sceProvider.enabled(false);

	}]).constant("API_BASE", api_base);

	//Bootstrap JS stuff
	$("#alert").alert();

})();