(function() {
	angular.module("m2t").controller("MainController",
		["$scope", "$http", "API_BASE", "$rootScope",
			function($scope, $http, API_BASE, $rootScope) {

		$rootScope.require_css = ["main.css"];

		$scope.error = "";
		$scope.added_hashes = [];
		$scope.recent_loaded = false;

		$http.get(API_BASE + "/info/recent").success(function(data) {
			$scope.recent = data.torrents;
			$scope.recent_loaded = true;
		});		

		$scope.submitUrls = function() {
			if (!$scope.urls) {
				$scope.error = "No URLs or Magnets specified";
				return;
			}
			var urls = $scope.urls.split("\n");
			for (var url in urls) {
				url = urls[url];
				$http.get(API_BASE + "/upload/" + encodeURIComponent(url)).success(function(data) {
					if (data.added) {
						$scope.added_hashes.push(data.hash);
					}
				}).error(function(data) {
					$scope.error += data.message + "<br />";
				});
			}
		}

		$scope.clearError = function($event) {
			console.log($event);
			$scope.error = "";
			return false;
		};

	}]);
})();