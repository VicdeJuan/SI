<?php require 'base/header.php'; ?>

<div class="body-container" ng-controller="movieListController">
	<aside class="menu">
		<ul class="menu-items"> 
			<filter title="Título" value="searchTitle" name"titleFilter" fallback="" allow-custom="string"></filter>			
			<filter title="Género" filters="genres" value="genreValue" name="genreFilter" fallback="" allow-custom="string" value-updated="genreUpdated(value)"></filter> 
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
			<div class="movie" ng-repeat="movie in movies | movieFilter:search | slice:startIndex | limitTo:pageLength as filtered">
				<div class="movie-cover">
					<img ng-src="{{movie.image}}" class="movie-img">
					<p class="movie-title">{{movie.title}}</p>
					<div class="movie-action">
						<p class="movie-price">{{movie.price}} €</p>
						<p class="movie-buy">
							<a ng-click="addToCart(movie)" href="">
								<img src="/img/cart.svg" class="movie-cart" />
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