<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style/main.css" />
  <script src="javascript/external/Crypto/rollups/md5.js" type="text/javascript"></script>
  <script src="javascript/submitLogin.js" type="text/javascript"></script>
  <meta charset="utf-8" />
  <title>Ola k ase</title>
</head>
<body>
<?php session_start();?>
  <header>
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
        <li><a href="">Carrito</a></li>
        <li><a href="php/exit.php">Salir</a></li>
      </ul>
    </div>
  </header>
  <div class="body-container">
    <aside class="menu">
      <ul>
        <li class="filter"><a href="index.html" class="filter-title">Inicio</a></li>
        <li class="filter"> <p class="filter-title"> Género</p>
          <ul>
            <li><a href="pages/error.html" class="filter-genre">Comedia</a></li>
            <li><a href="pages/error.html" class="filter-genre">Drama</a></li>
            <li><a href="pages/error.html" class="filter-genre">Etc</a></li>
          </ul>
        </li>
        <li class="filter"> <p class="filter-title"> Año </p></li>
        <li class="filter"> <p class="filter-title"> Etc </p></li>
      </ul>
    </aside>
    <iframe src="data/movies.xml" id="moviecatalog"></iframe>
  </div>
  <footer>
    <p class="footer-text">Olakase - Víctor de Juan Sanz - Guillermo Julián Moreno</p>
  </footer>
</body>
</html>
