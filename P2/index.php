<!DOCTYPE html>
<html lang="en">
<head>
  <link rel="stylesheet" type="text/css" href="style/main.css" />
  <script src="javascript/external/Crypto/rollups/md5.js" type="text/javascript"></script>
  <script src="javascript/submitLogin.js" type="text/javascript"></script>
  <meta charset="utf-8" />
  <title>Ola k ase</title>
</head>
</head>
<body>
    <?php
    /*  If we come from a registration  */
      $dir = "users/".$_POST['email'];
      $filename = $dir."/"."datos.dat";
      if (!is_dir($dir)){        /*User does not exist so we create it.*/
        mkdir($dir,0777);
        $myfile = fopen($filename, "w");
        $txt = $_POST['name']."\n";
        $txt = $txt.$_POST['email']."\n";
        $txt = $txt.$_POST['password']."\n";
        $txt = $txt.$_POST['creditCard']."\n";
        $txt = $txt.$_POST['CSV']."\n";
        $txt = $txt.$_POST['expireDate']."\n";
        $txt = $txt.date('d/m/Y\0', time())."\n
        ";
        fwrite($myfile, $txt);
        fclose($myfile);
        $ses = "Created";
      }else{      /* Now, if we come from a login */
        $myfile = fopen($filename, "r");
        if ($myfile){
          $name=fgets($myfile);
          $email = fgets($myfile);
          $password = strstr(fgets($myfile),"\n",true);

          if (0 == strcmp($password,$_POST['password'])){
            /* Correct
              TODO: md5
            */
            $ses = $name;
            setcookie("name", $name);
            setcookie("email", $email);
          } else{
            $ses = "MISMATCH: ".$_POST['password']." & ".$password;
            $name="";
            /* Mismatch */
          }
          fclose($myfile);
        } else{
        $ses = "Registrarse";
        }
      }
      ?>
  <header>
    <div class="header-logo">
      <p>Olakase</p>
    </div>

    <div class="header-options">
      <ul>
        <?php
          if($_COOKIE['name'] != "" && $name=""){
            $link = "register.html";
          }else{
            $link = "error.html";
          }
          print("<li><a href=".$link.">".$ses."</a></li>");
        ?>
          <li><a href="">Carrito</a></li>
          <li><a href="">Salir</a></li>
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
      <div class="scroller">
        <div class="inner-scroll">
          <div class="main-container">
            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Necessitatibus doloribus deserunt harum quos! Ipsa quia, reiciendis ullam, laborum veritatis omnis magni excepturi distinctio fugit vitae itaque rem, esse veniam officia?
              </p>
            </div>
            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolor est recusandae illo magni eveniet magnam repudiandae voluptate reprehenderit ullam, corporis assumenda perspiciatis, eum provident odio labore incidunt ipsum totam fugiat.
              </p>
            </div>

            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Reprehenderit veniam, voluptatibus nisi dolor error eaque expedita! Aut doloribus odio architecto, cupiditate, fuga consequatur labore quia recusandae expedita, quo quasi temporibus.
              </p>
            </div>

            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. 	Quisquam dolorem quos voluptas tempora molestias blanditiis id voluptate accusamus eveniet, cumque, eum alias quae provident officiis eligendi, necessitatibus repellat facere beatae!
              </p>
            </div>
            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vitae ab repudiandae minus quibusdam, obcaecati amet dolorum similique beatae nemo deserunt expedita hic officia eum corporis laudantium, dignissimos vero cupiditate at.
              </p>
            </div>

            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Blanditiis aperiam perferendis vero, nobis magnam praesentium ipsa, veritatis tenetur et itaque expedita quasi tempora illo enim labore assumenda voluptatem eligendi laborum.

              </p>
            </div>

            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Excepturi, quis perferendis aspernatur aliquam qui, rem porro similique debitis. Rerum ut enim, unde 	iusto optio, qui labore distinctio doloremque deserunt sit!
                vm kldja fkl
              </p>
            </div>
            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Natus expedita itaque modi iste! Vero architecto explicabo ullam. Voluptatem quas deleniti, error delectus natus saepe modi, dignissimos nesciunt quo quam cumque?
                vm kldja fkl
              </p>
            </div>

            <div class="movies">
              <img src="img/movie.jpg" class="movie_img">
              <p class="movie_title">Titulo
              </p>
              <p class="movie_descrip">
                Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nisi voluptate iste labore, alias maxime eligendi voluptatum eaque mollitia non dignissimos quam, voluptatibus architecto repellendus beatae iure rem, dicta, accusantium consectetur.
                vm kldja fkl
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>	<footer>
  <p class="footer-text">Olakase - Víctor de Juan Sanz - Guillermo Julián Moreno</p>
</footer>

</body>
</html>
