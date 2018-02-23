<?php

require("conexion.inc");
require("estilos_regional.inc");
echo "<form method='post' action=''>";
$sql = "select * from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
$resp = mysql_query($sql);
echo "<h1>Seleccione la linea de trabajo</h1>";

echo "<center><table class='texto'>";
echo "<tr><th>Linea</th><th>&nbsp;</th></tr>";
while ($dat = mysql_fetch_array($resp)) {
    $codigo = $dat[0];
    $nombre = $dat[1];
    $sql_filtro = "select codigo_linea from funcionarios_lineas where codigo_linea='$codigo' and codigo_funcionario='$global_usuario'";
    $resp_filtro = mysql_query($sql_filtro);
    $num_filas = mysql_num_rows($resp_filtro);
    if ($num_filas != 0) {
        echo "<tr><td>&nbsp;$nombre</td><td align='center'>
		<a href='cookie_linea_regional.php?linea=$codigo'><img src='imagenes/enter.png' width='40'></a></td></tr>";
    } else {
        echo "<tr><td>&nbsp;$nombre</td><td align='center'>-</td></tr>";
    } 
} 
echo "</table></center><br>";
require("home_regional.inc");
echo "</form>";

?>