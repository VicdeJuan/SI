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
				 		<form ng-click="$event.stopPropagation();" ng-submit="loginSubmit();" name="login-form"  class="login-form" ng-show="showLogin" >
							<label class="login-label"> Email: </label><input class="login-input" type="email" name="email" ng-model="email"> <br>
							<label class="login-label"> Contraseña:</label><input class="login-input" type="password" name="password" ng-model="password" autocomplete="off" required pattern="[a-zA-Z0-9]+" id="passwordfield"></p>
							<div id="messages" class="login-err-msg" ng-show="errLogin" > El email y la contraseña no se encuentran en la base de datos. </div>
				 			<p>
					  			<a href="register.php" id="NewRegister">¿No tienes cuenta todavía?</a>		
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