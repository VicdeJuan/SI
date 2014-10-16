var mainApp = angular.module('mainApp', []);

mainApp.controller('movieListController', function($scope){
	$scope.movies = [
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título',
			'description': "Patatas fritsa"
		},
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título123',
			'description': "aaaaa fritsa"
		},
		{ 
			'image' : 'img/movie.jpg',
			'title' : 'Título 2 ',
			'description': "Patatas asdasd"
		}
	];
});