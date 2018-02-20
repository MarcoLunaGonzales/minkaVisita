<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select cod_dist,descripcion from distritos where cod_ciudad in ($codTerritorio)";
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_distrito' class='texto' size='15' onChange='ajaxZona(this)' multiple>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo_visitador=$dat_visitador[0];
	$nombre_visitador="$dat_visitador[1]";
	echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
}
echo "</select>";
?>