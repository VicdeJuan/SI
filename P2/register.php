<?php require 'base/header.php'; ?>

<div class="body-container" id="login-body-container" data-ng-controller="registerController" data-ng-init="strengthMsg=''">
	<form action="/php/login_register.php" method="post" class="register-form">
		<p id="WelcomeMsg">¡Bienvenido!</p>

			<label class="login-label"> Nombre:</label> <input autofocus class="login-input" type="text" name="name" pattern="[a-zA-Z]+" required title="Los nombres solo tienen letras, ¿no?" >
			<label class="login-label"> Contraseña: </label> 
			<input type="password" name="password" data-ng-model="password" autocomplete="off" required >
			<div id="messages" data-ng-show="checkPass();" data-ng-class="{fuerte : strength >= 3, media : strength == 2, debil : strength == 1, invalid : strength == 0 }"
			> {{ strengthMsg }} </div><br>

			<label class="login-label"> E-mail:</label> <input class="login-input" type="email" name="email" autocomplete="off" required title="Introduzca una dirección de correo válida">
			<label class="login-label"> Tarjeta de crédito:</label> <input class="login-input" type="text" name="creditCard" required pattern="\d{16}" title="Introduzca un número de tarjeta de crédito válido."><br>
			
			<label class="login-label"> Fecha de caducidad (mm/yyyy):</label> <input class="login-input" type="text" name="expireDate" required pattern="\d{1,2}/\d{4}" title="Introduzca el mes y el año (completo) de caducidad de su tarjeta">
			<label class="login-label"> Codigo de seguridad:</label> <input class="login-input" type="text" name="CSV" required pattern="\d\d\d">
			<p id="ButtonRegister">
				<input type="submit" data-ng-disabled="!valid" name="Login" value="Registrarme">
			</p>
	</form>
</div>

<?php require 'base/footer.php'; ?>