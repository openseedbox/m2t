(function() {

	angular.module("m2t").directive("ajaxLoader", function() {
		return {
			"restrict" : "E",
			"replace" : true,
			"template" : "<div class='ajax-loader'><img src='images/ajax-loader.gif' /></div>",
			"scope" : {
				"indicator" : "="
			},
			"link" : function($scope, $element, $attrs) {
				$scope.$watch("indicator", function(value) {
					if (value) {
						$($element).hide();
					} else {
						$($element).show();
					}
				});
			}
		}
	});

})();