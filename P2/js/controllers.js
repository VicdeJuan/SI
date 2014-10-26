var mainApp = angular.module('mainApp', ['ngAnimate']);

var s = JSON.stringify;

var unique = function(arr, field) {
    var values = {},
        result = [];
    var l = arr.length,
        i = 0;

    for (i = 0; i < l; i += 1) {
        values[arr[i][field]] = arr[i];
    }

    for (i in values) {
        result.push(values[i]);
    }

    return result;
};

mainApp.filter('unique', function() {
    return unique;
});

mainApp.filter('slice', function() {
  return function(arr, start) {
    return (arr || []).slice(start);
  };
});

var cart = {
    items: [],

    find: function(title) {
        for (var i = this.items.length; i--;) {
            if (this.items[i]['title'] === title) {
                return this.items[i];
            }
        }
        return null;
    },

    add: function(toAdd) {
        var existing = this.find(toAdd['title']);

        if (existing != null)
            existing['quantity'] += 1;
        else {
            toAdd['quantity'] = 1;
            this.items.push(toAdd);
        }
    },

    remove: function(toRemove) {
        var existing = this.find(toRemove['title']);

        if (existing == null)
            return;

        existing['quantity'] -= 1;

        if (existing['quantity'] <= 0)
            remove(this.items, existing);
    },

    fetch: function($http) {
        $http.get('/php/cart.php')
            .success(function(data, status) {
                for (var i = data.length - 1; i >= 0; i--) {
                    cart.items.push(data[i]);
                }
            });
    }
};

function remove(arr, item) {
    for (var i = arr.length; i--;) {
        if (arr[i] === item) {
            arr.splice(i, 1);
        }
    }
}

mainApp.controller('headerController', ['$scope', '$http',
    function($scope, $http) {
        $scope.cartItems = cart.items;

        cart.fetch($http);

        $scope.removeFromCart = function(item) {
            $http.delete('/php/cart.php?itemId=' + item['title'])
                .success(function(data, status) {
                    cart.remove(item);
                })
                .error(function(data, status) {
                    alert('No se ha podido eliminar del carrito.');
                });
        };
    }
]);

mainApp.controller('movieListController', ['$scope', '$http',
    function($scope, $http) {
        $scope.fetchMovies = function() {
            $http.get('/php/movies.php', { params: {
                'count': $scope.pageLength,
                'from': $scope.lastRetrieved
            }})
            .success(function(data, status) {
                var movies = data['movies'];

                for (var i = 0; i < movies.length; i++) {
                    if ($scope.movies.indexOf(movies[i]) == -1)
                        $scope.movies.push(movies[i]);
                }

                $scope.lastRetrieved += movies.length;
            });
        };

        $scope.fetchIfNeeded = function() {
             if ($scope.filtered && $scope.filtered.length < $scope.pageLength)
                $scope.fetchMovies();
        };

        $scope.movies = [];
        $scope.startIndex = 0;
        $scope.availableLengths = [5, 10, 25, 50, 100];
        $scope.pageLength = 10;
        $scope.page = 1;
        $scope.lastRetrieved = 0;

        $scope.$watchGroup(['page', 'pageLength'], function(params) {
            var page = params[0];
            var pageLength = params[1];
            $scope.startIndex = (page - 1) * pageLength;

            $scope.fetchIfNeeded();
        });

        $scope.search = {
            genre: $scope.searchGenre
        };

        $scope.$watchGroup(['searchGenre'], function(searchGenre) {
            $scope.search.genre = searchGenre[0];
        });

        $scope.$watchCollection('filtered', $scope.fetchIfNeeded);


        $scope.addToCart = function(movie) {
            $http.post('/php/cart.php', {
                'item': movie
            })
                .success(function(data, status) {
                    cart.add(movie);
                })
                .error(function(data, status) {
                    alert('No se ha podido añadir al carrito.');
                });
        };
    }
]);
