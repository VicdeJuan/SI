<?php require 'base/header.php'; ?>

<div class="body-container" ng-controller="movieListController">
	<aside class="menu">
		<ul>
			<li class="filter">
				<span class="filter-title">Título</span>
				<p class="filter-items">
					<input type="text" ng-model="searchTitle" />
				</p>
			<li class="filter">
				<span class="filter-title">Género</span>
				<ul class="filter-items">
					<li ng-repeat="genre in genres">
						<input 
						type="radio" 
						value="{{genre}}" 
						class="filter-genre" 
						ng-model="$parent.searchGenre" 
						name="searchGenre">
							{{genre}}
						</input>
					</li>
					<li>
						<input 
						type="radio" 
						value="" 
						class="filter-genre" 
						ng-model="searchGenre" 
						name="searchGenre">
							Todos
						</input>
					</li>
				</ul>
			</li>

			<li class="filter">
				<span class="filter-title">Año</span>
				<ul class="filter-items">
					<li ng-repeat="year in years">
						<input 
						type="radio" 
						value="{{year.bounds}}" 
						class="filter-genre" 
						ng-model="$parent.yearRange" 
						name="year">
						{{year.name}}
					</input>
					</li>
					<li>
						<input ng-init="customYearRange = {}"
						type="radio" 
						value="custom"
						class="filter-genre" 
						ng-model="yearRange" 
						name="yearRange">
							Personalizado: 
							<input type="number" ng-model="customYearRange.min" /> 
							hasta 
							<input type="number" ng-model="customYearRange.max" />.
						</input>
					</li>
					<li>
						<input 
						type="radio" 
						value='{"min":0,"max":5000}'
						class="filter-genre" 
						ng-model="yearRange" 
						name="yearRange">
						Todos
						</input>
					</li>
				</ul>
			</li>

			<li class="filter">
				<span class="filter-title">Precio</span>
				<ul class="filter-items">
					<li ng-repeat="price in prices">
						<input 
						type="radio" 
						value="{{price.bounds}}" 
						class="filter-genre" 
						ng-model="$parent.priceRange" 
						name="price">
						{{price.name}}
					</input>
					</li>
					<li>
						<input ng-init="customPriceRange = {}"
						type="radio" 
						value="custom"
						class="filter-genre" 
						ng-model="priceRange" 
						name="priceRange">
						Personalizado: 
						<input type="number" ng-model="customPriceRange.min" /> hasta <input type="number" ng-model="customPriceRange.max" />.
						</input>
					</li>
					<li>
						<input 
						type="radio" 
						value='{"min":0,"max":5000}'
						class="filter-genre" 
						ng-model="priceRange" 
						name="priceRange">
						Todos
						</input>
					</li>
				</ul>
			</li>
		</ul>
	</aside>

	<div class="scroller">
		<div class="main-container">
			<p class="pagination-control"><a href="" ng-click="prevPage()">&lt;</a> <a href="" ng-click="nextPage()">&gt;</a> | {{startIndex}} - {{startIndex + filtered.length}} (
				<select ng-model="pageLength" ng-options="length for length in availableLengths">
				</select> resultados por página)
			</p>

			<div class="movies" ng-repeat="movie in movies | movieFilter:search | slice:startIndex | limitTo:pageLength as filtered">
				<img ng-src="{{movie.image}}" class="movie_img">
				<p class="movie_title">{{movie.title}}</p>
				<p class="movie_description">
					{{movie.price}} €: {{movie.description}}
				</p>
				<a ng-click="addToCart(movie)" href="">Añadir al carrito</a>
			</div>

			<p class="pagination-control"></p>
		</div>
	</div>
</div>

<?php require 'base/footer.php'; ?>
