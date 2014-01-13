(function() {

	var app = angular.module("m2t", ["ngRoute"]);	

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

	}]).constant("API_BASE", angular.element("#api_base").attr("href"));

	//Bootstrap JS stuff
	$("#alert").alert();

})();