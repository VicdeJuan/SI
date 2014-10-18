<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style/main.css" />
  <link rel="stylesheet" type="text/css" href="style/login.css" />
  <script src="javascript/external/Crypto/rollups/md5.js" type="text/javascript"></script>
  <script src="javascript/submitLogin.js" type="text/javascript"></script>
  <meta charset="utf-8" />
  <title>Ola k ase</title>
</head>
</head>
<body>
  <header>
    <div class="header-logo">
      <p>Olakase</p>
    </div>

    <div class="header-options">
      <ul>
      <li><a href=""><?php
        $dir = "users/".$_POST['email'];
        echo $_POST['email'];
        if (is_dir($dir)){
          /*User does not exist so we create it.*/
          $filename = $dir."/"."datos.dat";
          $myfile = fopen($filename, "r");
          $txt=fread($myfile,filesize($filename));
          $password = strstr($email, '!!password', true);
          if ($password == $_POST['password']){
            /*TODO: correct*/
            echo "Correct"
          } else{
            echo "Mismatch"
            /* Mismatch */
          }
          fclose($myfile);
        }else{
          echo "No creado";
          /*TODO: redirect.*/
        }
        ?></a></li>
      <li><a href="">Carrito</a></li>
      <li><a href="">Salir</a></li>
      </ul>
    </div>
  </header>
  <div class="body-container" id="login-body-container">
    <form action="index.xml" method="post">
      <p id="WelcomeMsg">¡Bienvenido!</p>
       <table>
        <tr>
          <td>
            <td>E-mail:</td><td> <input type="email" name="email" autocomplete="off" required title="Introduzca una dirección de correo válida">
          </td>
          <td>
            <td>Contraseña:</td><td> <input type="password" name="password" autocomplete="off" required pattern="[a-zA-Z0-9]+">
          </td>
        </tr>
      </table>
      <p id=WelcomeMsg>
        <input type="submit" name="Login" value="Registrarme">
        <input type="button" id="NewRegister" value="¿Sin cuenta todavía?" onclick="register.html">
      </p>
    </form>
  </div>
  </div>	<footer>
    <p class="footer-text">Olakase - Víctor de Juan Sanz - Guillermo Julián Moreno</p>
  </footer>

</body>
</html>
