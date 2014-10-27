<html lang="en" xmlns:ng="http://angularjs.org" ng-app="mainApp">
<head>
	<link rel="stylesheet" type="text/css" href="style/main.css" />
	<meta charset="utf-8" />
	<title>Ola k ase</title>
	<script src="lib/angular.min.js" type="text/javascript"></script>
	<script src="lib/angular-animate.min.js" type="text/javascript"></script>
	<script src="js/controllers.js" type="text/javascript"></script>
	<body>
		<header ng-controller="headerController" ng-init="showCart = false">
			<div class="header-logo">
				<p>Olakase</p>
			</div>
			<div class="header-options">
				<?php
				session_start();
				if($_SESSION['name'] == "" ){
					$link = "";
					$text = "Login";
				}else{
					$text = $_SESSION['name'];
					$link = "/pages/error.html";
				} ?>
				<ul ng-controller="loginSubmitController" ng-init="showLogin = false;loginTitle ='<?php echo $text; ?>'" >
					<li>
						<a ng-click="showLogin = !showLogin;" href="<?php echo $link; ?>">
							{{ loginTitle }}
						</a>
					</li>

					<div class="login-div" ng-show="showLogin" ng-click="showLogin = false" >
						<!-- TODO:  petición http y procesar código de error -->
						<form ng-click="$event.stopPropagation();" ng-submit="loginSubmit();" name="login-form"  class="login-form" ng-show="showLogin" >
							<table>
								<tr>
									<td>Email:</td>
									<td> <input type="email" name="email" ng-model="email" ></td>
								</tr>
								<tr>
									<td>Contraseña:</td>
									<td> <input type="password" name="password" ng-model="password" autocomplete="off" required pattern="[a-zA-Z0-9]+" id="passwordfield"></td>
								</tr>	
								<tr>
									<td colspan="2">
										<div id="messages" class="login-err-msg" ng-show="errLogin" > El email y la contraseña no se encuentran en la base de datos. </div>
									</td>
								</tr>
							</table>
							<p>
								<a href="register.html" id="NewRegister">¿No tienes cuenta todavía?</a>		
								<input type="submit" value="login" name="login" id="login-button">
							</p>			
						</form>
					</div>

					<li>
						<a href="" ng-click="showCart = !showCart">Carrito ({{cartItems.length}})</a>
					</li>
					<li><a href="/php/exit.php">Salir</a></li>
				</ul>
			</div>
			<div class="cart-container animate-show" ng-show="showCart">
				<div class="cart">
					<div class="cartItem" ng-repeat="item in cartItems">
						<a href="javascript:void()" ng-click="removeFromCart(item)" class="deleteButton"><img ng-src="img/close.svg"></a>
						<img class="cartItemImage" src="{{item.image}}" />
						<div class="cartItemPrice"><p>{{item.quantity}} x {{item.price}}€</p></div>
						<div class="cartItemTitle"><p>{{item.title}}</p></div>
					</div>
				</div>
				<div class="cartBuy">
					<div class="button">¡Comprar!</div>
				</div>
			</div>
		</header>

		<!-- header.html end -->
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

		<!-- footer.html begin -->
		<footer>
			<p class="footer-text">
				Olakase - Víctor de Juan Sanz - Guillermo Julián Moreno
			</p>
		</footer>

	</body>
</html>