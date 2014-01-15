(function() {

	angular.module("m2t").controller("TorrentController",
			["$scope", "$routeParams", "$http", "$rootScope", "API_BASE", function($scope, $routeParams, $http, $rootScope, API_BASE) {
		$scope.hash = $routeParams.hash;

		$scope.info = {};

		$scope.refresh = function($event) {
			if ($event) {
				$event.preventDefault();
			}

			$http.get(API_BASE + "/info/refresh/" + $scope.hash).success(function(data) {
				$http.get(API_BASE + "/info/" + $scope.hash).success(function(data) {
					$scope.info = data.torrent;			
				}).error(function(data) {
					$rootScope.error += data.message;
				});	
			});
				
		};

		$scope.refresh();

	}]);

})();