<?php require $_SERVER['DOCUMENT_ROOT'].'/base/header.php'; ?>

<div class="body-container" data-ng-controller="movieListController">
	<aside class="menu">
			<filter title="Título" value="search.title" name="titleFilter" fallback="emptyStrObject" allow-custom="string"></filter>			
			<filter title="Género" filters="genres" value="search.genre" name="genreFilter" fallback="emptyStrObject" allow-custom="string"></filter> 
			<filter title="Año" filters="years" value="search.year" name="yearFilter" fallback="defaultRange" allow-custom="range"></filter>
			<filter title="Precio" filters="prices" value="search.price" name="priceFilter" fallback="defaultRange" allow-custom="range"></filter>	
	</aside>

	<div class="scroller">
		<div class="scroller-top">
			<div class="pagination-control">
				<a href="" class="page-control" data-ng-class="prevDisabled" data-ng-click="prevPage()">‹</a> 
				<span>{{startIndex}} - {{startIndex + filtered.length}}</span>
				<a href="" class="page-control" data-ng-class="nextDisabled" data-ng-click="nextPage()">›</a>
			</div>
			<div class="pagination-config">
				<select data-ng-model="pageLength" data-ng-options="length for length in availableLengths">
				  </select>	resultados por página
			</div>
		</div>
		<div class="main-container" data-ng-class="movieHoverClass">
			<div class="movie" data-ng-repeat="movie in movies | movieFilter:search | slice:startIndex | limitTo:pageLength as filtered">
				<div class="movie-cover">
					<img alt="Imagen de la película" data-ng-src="{{movie.image}}" src="about:blank" class="movie-img">
					<p class="movie-title">{{movie.title}}</p>
					<div class="movie-action">
						<p class="movie-price">{{movie.price}} €</p>
						<p class="movie-buy">
							<a data-ng-click="addToCart(movie)" href="">
								<img  alt="Añadir al carrito" src="/img/cart.svg" class="movie-cart" />
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

<?php require $_SERVER['DOCUMENT_ROOT'].'/base/footer.php'; ?>
