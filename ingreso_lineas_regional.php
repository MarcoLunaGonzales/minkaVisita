<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * 
 * @autor : Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * *
 * @copyright 2006
 */

require("conexion.inc");
require("estilos_regional.inc");
echo "<form method='post' action=''>";
$sql = "select * from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
$resp = mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><th>Seleccione la Línea con la que desee trabajar</th></tr></table></center><br>";
echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
echo "<tr><th>Líneas de Productos</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $nombre = $dat[1];
    $sql_filtro = "select codigo_linea from funcionarios_lineas where codigo_linea='$codigo' and codigo_funcionario='$global_usuario'";
    $resp_filtro = mysql_query($sql_filtro);
    $num_filas = mysql_num_rows($resp_filtro);
    if ($num_filas != 0) {
        echo "<tr><td>&nbsp;$nombre</td><td align='center'><a href='cookie_linea_regional.php?linea=$codigo'>Ingresar >></a></td></tr>";
    } else {
        echo "<tr><td>&nbsp;$nombre</td><td align='center'>Ingresar >></td></tr>";
    } 
} 
echo "</table></center><br>";
require("home_regional.inc");
echo "</form>";

?>