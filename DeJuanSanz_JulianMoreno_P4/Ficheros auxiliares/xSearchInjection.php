<?php 
define("PGUSER", "alumnodb");
define("PGPASSWORD", "alumnodb");
define("DSN","pgsql:host=localhost;dbname=si1;options='--client_encoding=UTF8'");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"> 
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Ejemplo de SQL injection</title>
  </head>
  <body>
    <h2> Ejemplo de SQL injection: Información en la BD</h2>
      <form name="consultaanio" method="get" action="xSearchInjection.php">
        Películas del año: <input name="i_anio"/><br/>
        <input type="submit" value="Mostrar"/>
      </form>
    <?php
      $anio=$_GET["i_anio"];
      if (isset($anio)) {
        try {
            $consulta = "select movietitle from imdb_movies where year = '$anio'"; 

            $conn = new PDO(DSN,PGUSER,PGPASSWORD);
            $st = $conn->prepare($consulta);
            $st->execute();
            $resultado = $st->fetchAll();
            echo "<ol>\n";
            foreach ($resultado as $linea) {
                echo "<li>".$linea["movietitle"]."</li>\n";
            }
            echo "</ol>\n";
        } catch (PDOException $e) {
          print "Error!: " . $e->getMessage() . "<br/>";
        }

        $conn = null;
      }
    ?>
  </body>
</html>
