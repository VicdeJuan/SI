<?php require 'html_head.php'; ?>

<?php
	session_start();

	if($_SESSION['name'] == "" )
	{
		$text = "Login";
		$boolean_logged = false;
	}
	else
	{
		$boolean_logged = true;
		$text = $_SESSION['name'];
	} 
?>

<body>
<header ng-controller="headerController" ng-init="showCart = false">
	<div class="header-logo">
		<p><a href="/">Olakase</a></p>
	</div>

	<div class="header-options">
		<ul>
			<span ng-controller="loginSubmitController" ng-init="showLogin = false ;loginTitle ='<?php echo $text; ?>'" >
				<li>
					<a ng-click="loginTitleControl(<?php echo $boolean_logged?>);" ng-href="{{ loginLink }}">
						{{ loginTitle }}
					</a>
				</li>

					<div  class="login-div"  ng-show="showLogin" ng-click="showLogin = false" >
				 		<form ng-click="$event.stopPropagation();" ng-submit="loginSubmit();" name="login-form"  class="login-form" ng-show="showLogin" ng-class="errLogin ? login-form-small : login -form-big">
							<label class="login-label"> Email: </label><input class="login-input" type="email" name="email" ng-model="email"> <br>
							<label class="login-label"> Contraseña:</label><input class="login-input" type="password" name="password" ng-model="password" autocomplete="off" required id="passwordfield"></p>
							<div id="messages" class="login-err-msg" ng-show="errLogin" > El email y la contraseña no se encuentran en la base de datos. </div>
				 			<p>
					  			<a href="register.php" id="NewRegister">¿No tienes cuenta todavía?</a>		
					  			<input type="submit" value="login" name="login" id="login-button">
							</p>			
				 		</form>
				 	</div>
			</span>

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
			<div class="button" ng-click="processPurchase()">¡Comprar!</div>
		</div>
	</div>
</header>
