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

var safeParseInt = function(num, fallback) {
    var parsed = parseInt(num);

    if (isNaN(parsed))
        return fallback;
    else
        return parsed;
};

var checkBounds = function(item, field, bounds) {
    if (!bounds)
        return true;
    var min = safeParseInt(bounds.min, 0);
    var max = safeParseInt(bounds.max, Infinity);
    var value = item[field];

    return value >= min && value <= max;
};

var stringFilter = function(item, filter, onlyContain) {
    if (!filter)
        return true;

    if (onlyContain)
    {
        if(!filter || !filter.str)
            return true;

        return item && (item.toLowerCase().indexOf(filter.str.toLowerCase()) > -1);
    }
    else
    {
        return filter.str === item;
    }
}

mainApp.filter('movieFilter', function() {
    return function(movies, filter) {
        var filtered = [];

        angular.forEach(movies, function(movie) {
            if (stringFilter(movie['title'], filter.title, true) &&
                stringFilter(movie['genre'], filter.genre, true) &&
                checkBounds(movie, 'year', filter.year) &&
                checkBounds(movie, 'price', filter.price) &&
                checkBounds(movie, 'score', filter.score))
            {
                filtered.push(movie);
            }
        });

        return filtered;
     };
});

var cart = {
    items: [],

    find: function(id) {
        for (var i = this.items.length; i--;) {
            if (this.items[i]['id'] === id) {
                return this.items[i];
            }
        }
        return null;
    },

    add: function(toAdd) {
        var existing = this.find(toAdd['id']);

        if (existing != null)
            existing['quantity'] += 1;
        else {
            toAdd['quantity'] = 1;
            this.items.push(toAdd);
        }
    },

    remove: function(toRemove) {
        var existing = this.find(toRemove['id']);

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
    },
    removeAll: function($http) {
        $http.delete('/php/cart.php?all').success(function(){
            cart.items.length = 0;
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

        $scope.moviesIds = [];

        $scope.removeFromCart = function(item) {
            $http.delete('/php/cart.php?itemId=' + item['id'])
                .success(function(data, status) {
                    cart.remove(item);
                    $scope.showCart = false;
                })
                .error(function(data, status) {
                    alert('No se ha podido eliminar del carrito.');
                });
        };

 
        $scope.processPurchase = function(){
            $http.post('/api/history.php',$scope.cartItems)
                .success(function(data,status){
                    window.alert("Compra realizada con éxito");
                    cart.removeAll($http);

                })
                .error(function(data,status){
                    window.alert("Compra no realizada");
                });
        }
    }
]);


mainApp.controller('registerController', ['$scope',
    function($scope) {
        $scope.showStrength = false;
        $scope.strength = 1;
        $scope.strengthMsg = "";
        $scope.show = false;
        $scope.valid = false;   


        $scope.matching = function(str){
            count = 0;
            digits="[0-9]+";
            minus="[a-z]+";
            mayus="[A-Z]+";
            symbols="[\#\$\%\&\!\@]+";

            if(str.length < 5){
                return 0;
            }

            if (str.match(minus) != null) {
                count +=1;
            };
            if (str.match(mayus) != null) {
                count +=1;
            }
            if (str.match(digits) != null) {
                count += 1;
            };
            if (str.match(symbols) !=null) {
                count +=2;
            };
            return count;
        }


        $scope.checkPass = function(){    
            $scope.show = $scope.password && $scope.password.length > 0;

            if(!$scope.show)
                return false;

            $scope.strength = $scope.matching($scope.password);

            if($scope.strength == 0){
                $scope.strengthMsg = "Demasiado corta.";
                $scope.valid = false;
            }else{
                $scope.valid = true;
            }

            if ($scope.strength >= 4){
                $scope.strengthMsg = " Muy fuerte"; 
            }
            else if ($scope.strength >= 3){
                $scope.strengthMsg = "Fuerte"; 
            }
            else if ($scope.strength >= 2){
                $scope.strengthMsg = "Media"; 
            }
            else if ($scope.strength >= 1){
                $scope.strengthMsg = "Debil";
            };

           return $scope.show;

        };
    }
]);

mainApp.controller('movieListController', ['$scope', '$http', '$filter',
    function($scope, $http, $filter) {
        $scope.fetching = false;

        $scope.fetchMovies = function() {
            if ($scope.fetching || $scope.lastRetrieved == -1)
                return;

            $scope.fetching = true;

            $http.get('/api/movies.php', {
                params: {
                    'count': $scope.pageLength,
                    'from': $scope.lastRetrieved
                }
            })
                .success(function(data, status) {
                    var movies = data['movies'];

                    for (var i = 0; i < movies.length; i++) {
                        var movie = movies[i];

                        if (!$scope.movieIds[movie['id']]) {
                            $scope.movieIds[movie['id']] = true;
                            $scope.movies.push(movie);
                        }
                    }

                    $scope.lastRetrieved = data['next'];

                    if ($scope.lastRetrieved == -1)
                        $scope.serverMovieCountLimit = $scope.movies.length;

                    $scope.fetching = false;

                    $scope.fetchIfNeeded();
                });
        };

        $scope.fetchIfNeeded = function() {
            if ($scope.filtered && $scope.filtered.length < $scope.pageLength)
                $scope.fetchMovies();
        };

        $scope.movieIds = {};
        $scope.movies = [];
        $scope.startIndex = 0;
        $scope.availableLengths = [5, 10, 25, 50, 100];
        $scope.pageLength = 10;
        $scope.lastRetrieved = 0;
        $scope.serverMovieCountLimit = Infinity;
        $scope.filterMovieCountLimit = Infinity;
        $scope.genres = [];
        $scope.emptyStrObject = { str: "" };
        $scope.search = {};

        var range = function(rangeName, minValue, maxValue) {
            return { name: rangeName, value: { min: minValue, max: maxValue }}
        };

        $scope.defaultRange = { min: -Infinity, max: Infinity };
        $scope.years = [
            range('< 1940', 0, 1940),
            range('40s-60s', 1940, 1969),
            range('70s', 1970, 1979),
            range('80s', 1980, 1989),
            range('90s', 1990, 1999),
            range('2000s', 2000, 2009),
            range('> 2010', 2010, Infinity)
        ];

        $scope.prices = [ 
            range('Gratis', 0, 0),
            range('1 - 5 €', 1, 5),
            range('5 - 10 €', 5, 10)
        ];

        $http.get('/api/genres.php')
            .success(function(data, status) {
                angular.forEach(data, function(genre) {
                    $scope.genres.push({
                        name: genre,
                        value: { str: genre }
                    });
                });
            });


        $scope.$watch('search', function() {
            console.log(s($scope.search));

            $scope.startIndex = 0;
            $scope.updateMovieCountLimit();
        }, true);

        $scope.updateMovieCountLimit = function() {
            if (isFinite($scope.serverMovieCountLimit))
                $scope.filterMovieCountLimit = $filter('movieFilter')($scope.movies, $scope.search).length;
        };

        $scope.$watch('serverMovieCountLimit', $scope.updateMovieCountLimit);

        $scope.$watchCollection('filtered', $scope.fetchIfNeeded);

        $scope.canGoPrevPage = function() {
            var newIndex = $scope.startIndex - $scope.pageLength;

            return newIndex >= 0;
        };

        $scope.canGoNextPage = function () {
            var newIndex = $scope.startIndex + $scope.pageLength;

            return newIndex < $scope.filterMovieCountLimit;
        };

        $scope.prevPage = function() {
            if ($scope.canGoPrevPage())
                $scope.startIndex = $scope.startIndex - $scope.pageLength;
        };

        $scope.nextPage = function() {
            if ($scope.canGoNextPage())
                $scope.startIndex = $scope.startIndex + $scope.pageLength;
        };

        $scope.updatePaginationControls = function () {
            $scope.prevDisabled = $scope.canGoPrevPage() ? "" : "disabled";
            $scope.nextDisabled = $scope.canGoNextPage() ? "" : "disabled";
        }

        $scope.$watchGroup(['startIndex','pageLength','filterMovieCountLimit'], $scope.updatePaginationControls);

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

mainApp.controller('loginSubmitController', ['$scope','$http','$timeout', function($scope,$http) {
	$scope.showLogin = false;
    $scope.errLogin = false;
	$scope.logged = false;
    
    $scope.loginTitleControl = function(logged){
        
        if (!$scope.logged) {
            $scope.loginLink = "";
            $scope.showLogin = true;
        };

        if($scope.logged || logged){
            $scope.loginLink = "history.php";
            $scope.showLogin = false;
        }

    }

	$scope.loginSubmit = function(user){
		$http({
			method: 'POST',
			url: '/php/login_register.php',
			data: {email: $scope.email, password: $scope.password}})
		.success(function(data,status){
			$scope.status = status;
			$scope.data = data;
			$scope.showLogin = false;
			$scope.errLogin = false;
            $scope.loginTitle = data['name'];
			$scope.logged = true;
            $scope.loginLink = "history.php";

		}).error(function(data,status){
			$scope.status = status;
			$scope.data = data || "Request failed";
			$scope.errLogin = true;
			$scope.showLogin = true;
            $scope.loginLink = "";

		});
	};
}]);

mainApp.controller('historyController', function($scope) { });

mainApp.controller('footerController', ['$scope', '$http', function($scope, $http) {
    $scope.date = "...";
    $scope.activeUsers = "...";

    $http.get('/api/webinfo.php')
    .success(function(data, status) {
        $scope.date = data['date'];
        $scope.activeUsers = data['active_users'];
    });
}]);