<?php
/**
 * 
 * @autor : Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * @copyright 2006
 */
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form method='post' action=''>";
$sql = "select * from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
$resp = mysql_query($sql);

echo "<h1>Grillas por Linea </h1>";

echo "<center><table class='zebra'>";
echo "<tr><th>Lineas de Productos</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $nombre = $dat[1];
    echo "<tr><td>&nbsp;$nombre</td><td align='center'><a href='grilla_ciudades.php?codigo_linea=$codigo'>Ver >></a></td></tr>";
} 
echo "</table></center><br>";
echo "</form>";

?>