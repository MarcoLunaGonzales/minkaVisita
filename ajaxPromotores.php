<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select distinct(f.cod_zeus), f.paterno, f.materno, f.nombres, 
	ci.nombre_ciudad
	from funcionarios f, cargos c, ciudades ci
	where f.cod_cargo=c.cod_cargo 
	and f.cod_cargo='1012' and f.estado=1 and f.cod_ciudad in ($codTerritorio) 
	and f.cod_ciudad=ci.cod_ciudad order by ci.nombre_ciudad,f.paterno";
	//echo $sql_visitador;
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_promotor' class='texto' size='15' multiple>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo_visitador=$dat_visitador[0];
	$nombre_visitador="$dat_visitador[1] $dat_visitador[3]";
	$ciudadX=$dat_visitador[4];
	echo "<option value='$codigo_visitador'>$nombre_visitador ($ciudadX)</option>";
}
echo "</select>";
?>