<?php require 'base/header.php'; ?>

<div class="body-container" id="login-body-container">
	<form action="/php/login_register.php" method="post">
		<p id="WelcomeMsg">¡Bienvenido!</p>
		<table>
			<tr>
				<td>
					<td>Nombre:</td><td> <input type="text" name="name" pattern="[a-zA-Z]+" required title="Los nombres solo tienen letras, ¿no?" >
				</td>
				<td>
					<td>Tarjeta de crédto:</td><td> <input type="text" name="creditCard" required pattern="\d{16}" title="Introduzca un número de tarjeta de crédito válido."></span>
				</td>
			</tr>
			<tr>
				<td>
					<td>E-mail:</td><td> <input type="email" name="email" autocomplete="off" required title="Introduzca una dirección de correo válida">
				</td>
				<td>
					<td>Fecha de caducidad:</td><td> <input type="text" name="expireDate" required pattern="\d{1,2}/\d{4}" title="Introduzca el mes y el año (completo) de caducidad de su tarjeta"></span>
				</td>
			</tr>
			<tr>
				<td>
					<td>Contraseña:</td><td> <input type="password" name="password" autocomplete="off" required pattern="[a-zA-Z0-9]+" id="passwordfield">
				</td>
				<td>
					<td>Codigo de seguridad:</td><td> <input type="text" name="CSV" required pattern="\d\d\d"></span>
				</td>
			</tr>
		</table>
		<p id="WelcomeMsg">
			<input type="submit" name="Login" value="Registrarme">
		</p>
	</form>
</div>
</div>

<?php require 'base/footer.php'; ?>