mainApp.directive('filter', function() {
    return {
        restrict: 'AE', // Only in elements and attributes
        scope: {
            filters: '=',
            title: '@',
            value: '=',
            fallback: '=',
            valueFormat: '@',
            allowCustom: '@',
            valueUpdated: '&'
        },
        templateUrl: '/base/filter.html',
        controller: ['$scope', function($scope) {
            if ($scope.filters)
            {
                $scope.filtersEnabled = true;
                $scope.filtersDisabledClass = "voidclass";
            }
            else
            {
                $scope.filtersDisabledClass = "filters-disabled";
            }

            $scope.customRangeEnabled = $scope.allowCustom === "range";
            $scope.customTextEnabled = $scope.allowCustom === "string";

            if($scope.valueFormat === "json")
                $scope.value = {};
            else
                $scope.value = "";

            $scope.getCustomValue = function() {
                if($scope.allowCustom === "range")
                    return $scope.customRange;
                else if($scope.allowCustom === "string")
                    return $scope.customText;
                else
                    return null;
            };

            $scope.isCustomValueActive = function () {
                return $scope.internalValue === "custom" || !$scope.filtersEnabled;
            }

            $scope.$watch('internalValue', function() {
                if ($scope.isCustomValueActive())
                    $scope.value = $scope.getCustomValue();
                else if($scope.valueFormat != "json")
                    $scope.value = $scope.internalValue;
                else if($scope.internalValue)
                    $scope.value = JSON.parse($scope.internalValue) || $scope.fallback;
                else
                    $scope.value = $scope.fallback;

                $scope.valueUpdated({ value: $scope.value });
            });

            $scope.isFirstValueUpdate = true;

            $scope.$watch('value', function() {
                if($scope.isFirstValueUpdate)
                { 
                    $scope.$evalAsync(function() {
                        $scope.value = angular.copy($scope.internalValue);
                    });
                }
            });

            $scope.$watch('customText', function () {
                if($scope.isCustomValueActive())
                    $scope.value = $scope.customText;
            }); 
        }]
    }
});