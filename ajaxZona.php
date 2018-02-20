<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select cod_zona,zona from zonas where cod_dist in ($codTerritorio)";
//echo $sql_visitador;
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_zona' class='texto' size='15' multiple>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo_visitador=$dat_visitador[0];
	$nombre_visitador="$dat_visitador[1]";
	echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
}
echo "</select>";
?>