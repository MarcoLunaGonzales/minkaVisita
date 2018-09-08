<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select cod_cliente, nombre_cliente from clientes
	where cod_ciudad=$codTerritorio order by 2";
	//echo $sql_visitador;
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_cliente' class='texto' size='10'>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo_visitador=$dat_visitador[0];
	$nombre_visitador=$dat_visitador[1];
	echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
}
echo "</select>";
?>