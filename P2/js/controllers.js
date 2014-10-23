var mainApp = angular.module('mainApp', ['ngAnimate']);

function unique(arr) {
    var u = {},
        a = [];
    for (var i = 0, l = arr.length; i < l; ++i) {
        if (!u.hasOwnProperty(arr[i])) {
            a.push(arr[i]);
            u[arr[i]] = 1;
        }
    }
    return a;
}

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
}

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
                    alert("No se ha podido eliminar del carrito.");
                });
        };
    }
]);

mainApp.controller('movieListController', ['$scope', '$http',
    function($scope, $http) {
        $scope.movies = [];

        $http.get('/php/movies.php', {
            'count': 4
        })
            .success(function(data, status) {
                $scope.movies = data['movies'];
            });

        $scope.genres = unique($scope.movies.map(function(movie) {
            return movie['genre'];
        }));

        $scope.addToCart = function(movie) {
            $http.post('/php/cart.php', {
                'item': movie
            })
                .success(function(data, status) {
                    cart.add(movie);
                })
                .error(function(data, status) {
                    alert("No se ha podido añadir al carrito.");
                });
        }
    }
]);