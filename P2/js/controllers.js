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

mainApp.controller('headerController', function($scope) {
	$scope.cartItems = [
		{
			'image': 'img/movie.jpg',
			'title' : 'Pulp Fiction',
			'description': "Muere gente",
			'genre': 'Terror',
			'price': 3,
			'quantity': 2
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Pulp Foction',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 3,
			'quantity': 2
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Pelp Foction',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 28,
			'quantity': 2
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Pilp Foction',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 5,
			'quantity': 2
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Pilp Fuction',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 4,
			'quantity': 1
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Polo Fusión',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 4,
			'quantity': 1
		},
		{
			'image': 'img/movie.jpg',
			'title' : 'Pelé Fashion',
			'description': "Patatas fritsa",
			'genre': 'Terror',
			'price': 4,
			'quantity': 1
		},
	];
});

mainApp.controller('movieListController', function($scope){
	$scope.movies = [
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título',
			'description': "Patatas fritsa",
			'genre': 'Terror'
		},
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título123',
			'description': "aaaaa fritsa",
			'genre': 'Patat'
		},
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título 2 ',
			'description': "Patatas asdasd",
			'genre': "Comedia"
		}
	];

	$scope.genres = unique($scope.movies.map(function(movie) {
		return movie['genre'];
	}))
});