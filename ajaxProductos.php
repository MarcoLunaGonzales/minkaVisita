<?php
require('conexion.inc');
$codLinea=$_GET['codLinea'];
$rptLineaX= str_replace("`","'",$codLinea);

$sql_visitador="select cod_producto, nombre_producto from productos
	where linea in ($rptLineaX) order by 2";
	//echo $sql_visitador;
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_producto' class='texto' size='10'>";
while($dat=mysql_fetch_array($resp_visitador))
{	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>