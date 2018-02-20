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
echo "<center><table border='0' class='textotit'><tr><th>Grupos Especiales por Linea </th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
echo "<tr><th>Líneas de Productos</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $nombre = $dat[1];
    echo "<tr><td>&nbsp;$nombre</td><td align='center'><a href='gruposespeciales_ciudades.php?codigo_linea=$codigo'>Ver >></a></td></tr>";
}
 
echo "</table></center><br>";
echo "</form>";

?>