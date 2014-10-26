var mainApp = angular.module('mainApp', ['ngAnimate']);

function unique(arr) {
	var u = {}, a = [];
	for(var i = 0, l = arr.length; i < l; ++i){
		if(!u.hasOwnProperty(arr[i])) {
			a.push(arr[i]);
			u[arr[i]] = 1;
		}
	}
	return a;
}

var cart = {
	items: [],

	find: function(title) {
		for(var i = this.items.length; i--;) {
			if(this.items[i]['title'] === title) {
				return this.items[i];
			}
		}
		return null;
	},

	add: function(toAdd) {
		var existing = this.find(toAdd['title']);

		if(existing != null)
			existing['quantity'] += 1;
		else
		{
			toAdd['quantity'] = 1;
			this.items.push(toAdd);
		}
	},

	remove: function(toRemove) {
		var existing = this.find(toRemove['title']);

		if(existing == null)
			return;

		existing['quantity'] -= 1;

		if(existing['quantity'] <= 0)
			remove(this.items, existing);
	}
}

function remove(arr, item) {
	for(var i = arr.length; i--;) {
		if(arr[i] === item) {
			arr.splice(i, 1);
		}
	}
}

mainApp.controller('headerController', function($scope) {
	$scope.cartItems = cart.items;

	$scope.removeFromCart = function(item) {
		cart.remove(item);
	};


});

mainApp.controller('loginSubmitController', ['$scope','$http','$timeout', function($scope,$http) {

	/* Esto funcionaba. Utilizar angular para lo que sirve y no hacerlo así.*/
	/*$scope.loginHandler = function(show){
		if (show) {
			document.getElementById("login-div").style.zIndex = 3;
			document.getElementById("login-div").style.background = "rgba(245,221,222,0.65)";
			document.getElementById("login-form").style.opacity = 1;
			document.getElementById("login-form").style.background = "rgba(204,102,0,1)";
			document.getElementById("body-container").style.opacity = 0;
		}else{
			document.getElementById("login-div").style.zIndex = 0;
			document.getElementById("login-div").style.background = "rgba(245,221,222,0)";
			document.getElementById("login-form").style.background = "rgba(204,102,0,0)";
			document.getElementById("login-form").style.opacity = 0;
			document.getElementById("body-container").style.opacity = 1;
		};	
	};*/

	$scope.showLogin = false;

	$scope.loginSubmit = function(user){
		$http({
			method: 'POST',
			url: '/php/login_register.php',
			data: {email: $scope.email, password: $scope.password}})
		.success(function(data,status){
			$scope.status = status;
			$scope.data = data;
			$scope.loginHandler(true);
		}).error(function(data,status){
			$scope.status = status;
			$scope.data = data || "Request failed";


		});
	};
}]);


mainApp.controller('movieListController', ['$scope', '$http', function($scope, $http) {
	$scope.movies = [];

	$http.get('/php/movies.php', { 'count': 2 })
	.success(function(data, status) {
		$scope.movies = data['movies'];
	});

	$scope.genres = unique($scope.movies.map(function(movie) {
		return movie['genre'];
	}));

	$scope.addToCart = function(movie) {
		cart.add(movie);
	}
}]);