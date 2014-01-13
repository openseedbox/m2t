(function() {
	angular.module("m2t").controller("MainController", ["$scope", "$http", "API_BASE", function($scope, $http, API_BASE) {		

		$scope.error = "";
		$scope.added_hashes = [];

		$http.get(API_BASE + "/info/recent").success(function(data) {
			$scope.recent = data.torrents;
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