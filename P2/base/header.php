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
<header data-ng-controller="headerController" data-ng-init="showCart = false; showLogin = false; loginTitle ='<?php echo $text; ?>'; logged = <?php echo $logged; ?>;">
	<div class="header-logo">
		<p><a href="<?php echo $applicationBaseDir; ?>">Olakase</a></p>
	</div>

	<div class="header-options">
		<ul>	
			<li>
				<a data-ng-click="loginTitleControl(<?php echo $logged; ?>);" data-ng-href="{{ loginLink }}">
					{{ loginTitle }}
				</a>
				<div class="login-div"  data-ng-show="showLogin" data-ng-click="showLogin = false" >
			 		<form data-ng-click="$event.stopPropagation();" data-ng-submit="loginSubmit();" name="login-form"  class="login-form" data-ng-show="showLogin" data-ng-class="errLogin ? login-form-small : login -form-big">
						<label class="login-label"> Email: </label><input class="login-input" type="email" id="email-login" value="oscarwilde" name="email" data-ng-model="email" > <br>
						<label class="login-label"> Contraseña:</label><input class="login-input" type="password" name="password" data-ng-model="password" autocomplete="off" required id="passwordfield">
						<div id="messages" class="login-err-msg" data-ng-show="errLogin" > El email y la contraseña no se encuentran en la base de datos. </div>
			 			<p>
				  			<a href="<?php echo $applicationBaseDir; ?>register.php" id="NewRegister">¿No tienes cuenta todavía?</a>		
				  			<input type="submit" value="login" name="login" id="login-button">
						</p>			
			 		</form>
			 	</div>
			</li>	

			<li>
				<a href="" data-ng-click="showCart = !showCart">Carrito ({{cartItems.length}})</a>
			</li>

			<li><a href="<?php echo $applicationBaseDir; ?>php/exit.php">Salir</a></li>
		</ul>
	</div>

	<div class="cart-container animate-show" data-ng-show="showCart">
		<div class="cart">
			<div class="cartItem" data-ng-repeat="item in cartItems">
				<a href="" data-ng-click="removeFromCart(item)" class="deleteButton"><img alt="Cerrar" src="img/close.svg"></a>
				<img alt="Imagen de la pelicula" class="cartItemImage" data-ng-src="{{item.image}}" src="about:blank" />
				<div class="cartItemPrice"><p>{{item.quantity}} x {{item.price}}€</p></div>
				<div class="cartItemTitle"><p>{{item.title}}</p></div>
			</div>
		</div>
		<div class="cartBuy">
			<div class="button" data-ng-click="processPurchase()" data-ng-class="cartEnabled">
				<span data-ng-show="logged">¡Comprar!</span>
				<span data-ng-hide="logged">Regístrate para continuar</span>
			</div>
		</div>
	</div>
</header>
