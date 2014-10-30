<?php require 'base/header.php'; ?>

<div class="body-container" ng-controller="movieListController">
	<aside class="menu">
		<ul class="menu-items"> 
			<filter title="Título" value="searchTitle" name"titleFitler" fallback="" allow-custom="string"></filter>			
			<filter title="Género" filters="genres" value="genreValue" name="genreFilter" fallback="" allow-custom="string"></filter> 
			<filter title="Año" filters="years" value="yearValue" name="yearFilter" fallback="defaultRange" value-format="json" allow-custom="range"></filter>
			<filter title="Precio" filters="prices" value="priceValue" name="priceFilter" fallback="defaultRange" value-format="json" allow-custom="range"></filter>	
		</ul>
	</aside>

	<div class="scroller">
		<div class="scroller-top">
			<div class="pagination-control">
				<a href="" class="page-control" ng-class="prevDisabled" ng-click="prevPage()">‹</a> 
				<span>{{startIndex}} - {{startIndex + filtered.length}}</span>
				<a href="" class="page-control" ng-class="nextDisabled" ng-click="nextPage()">›</a>
			</div>
			<div class="pagination-config">
				<select ng-model="pageLength" ng-options="length for length in availableLengths">
				  </select>	resultados por página
			</div>
		</div>
		<div class="main-container" ng-class="movieHoverClass">
			<div class="movies" ng-mouseenter="$parent.movieHoverClass = 'movie-hovering'" ng-mouseleave="$parent.movieHoverClass = 'voidclass'" 
				ng-repeat="movie in movies | movieFilter:search | slice:startIndex | limitTo:pageLength as filtered">
				<div class="movie-cover">
					<img ng-src="{{movie.image}}" class="movie_img">
					<p class="movie_title">{{movie.title}}</p>
					<div class="movie_action">
						<p class="movie_price">{{movie.price}} €</p>
						<p class="movie_buy">
							<a ng-click="addToCart(movie)" href="">
								<img src="/img/cart.svg" class="movie_cart" />
							</a>
						</p>
					</div>
				</div>

				<div class="movie-info">
					<p>
					{{movie.genre}}<br />
					{{movie.year}}
					<p>

					<p class="movie-description">{{movie.description}}</p>
				</div>
			</div>
			<p class="pagination-control"></p>
		</div>
	</div>
</div>

<?php require 'base/footer.php'; ?>