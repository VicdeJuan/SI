<?php require $_SERVER['DOCUMENT_ROOT'].'/base/html_head.php'; ?>

<?php
	session_start();

	if(isset($_SESSION['name']) && $_SESSION['name'])
	{
		$boolean_logged = TRUE;
		$text = $_SESSION['name'];
	}
	else
	{
		$boolean_logged = FALSE;
		$text = "Login";
}

	$logged = $boolean_logged ? "true" : "false";
?>

<body>
<header ng-controller="headerController" ng-init="showCart = false; showLogin = false; loginTitle ='<?php echo $text; ?>'; logged = <?php echo $logged; ?>;">
	<div class="header-logo">
		<p><a href="<?php echo $applicationBaseDir; ?>">Olakase</a></p>
	</div>

	<div class="header-options">
		<ul>	
			<span>
				<li>
					<a ng-click="loginTitleControl(<?php echo $logged; ?>);" ng-href="{{ loginLink }}">
						{{ loginTitle }}
					</a>
				</li>

					<div  class="login-div"  ng-show="showLogin" ng-click="showLogin = false" >
				 		<form ng-click="$event.stopPropagation();" ng-submit="loginSubmit();" name="login-form"  class="login-form" ng-show="showLogin" ng-class="errLogin ? login-form-small : login -form-big">
							<label class="login-label"> Email: </label><input class="login-input" type="email" id="email-login" value="oscarwilde" name="email" ng-model="email" > <br>
							<label class="login-label"> Contraseña:</label><input class="login-input" type="password" name="password" ng-model="password" autocomplete="off" required id="passwordfield">
							<div id="messages" class="login-err-msg" ng-show="errLogin" > El email y la contraseña no se encuentran en la base de datos. </div>
				 			<p>
					  			<a href="<?php echo $applicationBaseDir; ?>register.php" id="NewRegister">¿No tienes cuenta todavía?</a>		
					  			<input type="submit" value="login" name="login" id="login-button">
							</p>			
				 		</form>
				 	</div>
			</span>

			<li>
				<a href="" ng-click="showCart = !showCart">Carrito ({{cartItems.length}})</a>
			</li>

			<li><a href="<?php echo $applicationBaseDir; ?>php/exit.php">Salir</a></li>
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
			<div class="button" ng-click="processPurchase()" ng-class="cartEnabled">
				<span ng-show="logged">¡Comprar!</span>
				<span ng-hide="logged">Regístrate para continuar</span>
			</div>
		</div>
	</div>
</header>
