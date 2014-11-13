<?php

/**
 *	Ejercicio BD-2 de sistemas informáticos.
 *
 *	Autores: Víctor de Juan Sanz y Guillermo Julián Moreno 
 * 
 */



// Conexion, seleccion de base de datos
$conn = pg_connect("host=localhost dbname=pelis user=alumnodb password=alumnodb")
 or die('No pudo conectarse: ' . pg_last_error());

// Realizar una consulta SQL
$consulta = 'SELECT * FROM Ejercicio2()';
$resultado = pg_query($conn, $consulta) or die('Consulta fallida: ' . pg_last_error());

// Impresion de resultados en HTML
echo "<table>\n";

while ($linea = pg_fetch_array($resultado, null, PGSQL_ASSOC)) {
 echo "\t<tr>\n";
 foreach ($linea as $valor_col) {
 echo "\t\t<td>$valor_col</td>\n";
 }
 echo "\t</tr>\n";
}

echo "</table>\n";

// Liberar conjunto de resultados y cerrar conexión
pg_free_result($resultado);
pg_close($conn);
?>