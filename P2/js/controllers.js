var mainApp = angular.module('mainApp', []);

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