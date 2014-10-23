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
				<ul>
					<?php
					if($_SESSION['name'] == "" && $_SESSION['ses'] == ""){
						$link = "register.html";
						$ses = "Registrarse";
					}else{
						$ses = $_SESSION['ses'];
						$link = "pages/error.html";
					}
					print("<li><a href=".$link.">".$ses."</a></li>");
					?>
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
 			<li class="filter"><a href="index.html" class="filter-title">Inicio</a></li>
 			<li class="filter">
 				<span class="filter-title">Género</span>
 				<ul class="filter-items">
 					<li ng-repeat="genre in genres">
 						<a href="pages/error.html" class="filter-genre">{{genre}}</a>
 					</li>
 				</ul>
 			</li>
 			<li class="filter"><span class="filter-title">Año</span></li>
 			<li class="filter"><span class="filter-title">Etc</span></li>
 		</ul>
 	</aside>

 	<div class="scroller">
 		<div class="inner-scroll">
 			<div class="main-container" 
 				ng-controller="movieListController">
 				<div class="movies" ng-repeat="movie in movies">
 					<img ng-src="{{movie.image}}" class="movie_img">
 					<p class="movie_title">{{movie.title}}</p>
 					<p class="movie_description">
 						{{movie.description}}
 					</p>
 					<a ng-click="addToCart(movie)" href="">Añadir al carrito</a>
 				</div>
 			</div>
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