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

	/* Utilizar angular para lo que sirve y no hacerlo así.*/
	$scope.loginHandler = function(show){
		if (show) {
			document.getElementById("login-div").style.zIndex = 3;
			document.getElementById("login-div").style.background = "rgba(245,221,222,0.65)";
			document.getElementById("login-form").style.opacity = 1;
			document.getElementById("body-container").style.opacity = 0;
			document.getElementById("login-form").style.background = "rgba(204,102,0,1)";
		}else{
			document.getElementById("body-container").style.opacity = 1;
			document.getElementById("login-div").style.zIndex = 0;
			document.getElementById("login-div").style.background = "rgba(245,221,222,0)";
			document.getElementById("login-form").style.background = "rgba(204,102,0,0)";
			document.getElementById("login-form").style.opacity = 0;
		};	
	};


});

mainApp.controller('pruebaController', ['$scope','$http','$timeout', function($scope,$http) {

	//$httpProvider.defaults.headers.post['ContentType'] = 'application/x-www-form-urlencoded;charset=utf-8';

	//Plan A! Hace post pero con datos nulos 
	$scope.loginSubmit = function(user){
		window.alert($scope.email);
		window.alert($scope.password);
		$http({
			method: 'GET',
			url: '/php/login_register.php',
			headers: {'contentType': 'application/x-www-form-urlencoded'},
			transformRequest: function(obj) {
				var str = [];
				for (var p in obj){
					if(obj.hasOwnProperty(p)){
						str.push(encodeURI(p) + "=" + encodeURIComponent(obj[p]));
					}
				}
				window.alert(str.join("&"));
				return str.join("&");
			},
			data: {'email': $scope.email, password: $scope.password}})
		.success(function(data,status){
			$scope.status = status;
			$scope.data = data;
			window.alert('SI');
		}).error(function(data,status){
			$scope.status = status;
			$scope.data = data || "Request failed";
			window.alert('NO');
		});
	};
}]);

	/** Plan B. Procesar en php */
	/*$scope.loginSubmit = function(user){
		window.alert($scope.email);
		window.alert($scope.password);
		$http({
			method: 'POST',
			url: '/php/login_register.php',
			data: {'email': $scope.email, password: $scope.password}})
		.success(function(data,status){
			$scope.status = status;
			$scope.data = data;
			window.alert('SI');
		}).error(function(data,status){
			$scope.status = status;
			$scope.data = data || "Request failed";
			window.alert('NO');
		});
	};
}]);*/


	/*$scope.email = element(by.binding('email'));
	$scope.password = element(by.binding('password'));
	
	var config = {
		params: {
			'name': $scope.name,
			'email': $scope.email,
			'subjetList': $scope.subjetList,
			'url': "/php/login_register.php",
		}
	};
	

	$scope.loginSubmit = function($scope,$http){
		$http.post('/php/login_register.php',config)
		.success(function(data,status,headers,config){
			if (data.status == 'OK') {
				headerController.loginHandler(false);
				$scope.message = null;
			}else{
				headerController.loginHandler(true);
				$scope.message = 'La contraseña y el error no corresponden';
			};
		}).error(function(){$scope.messages = 'Network error';

		}).finally (function(){
			$timeout (function(){
				$scope.message = null;
			},3000);
		});
	};}]);*/

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