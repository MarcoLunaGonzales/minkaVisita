<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql="select distinct(l.`codigo_linea`), l.`nombre_linea`
			from `lineas` l,
     `funcionarios_lineas` fl,
     `funcionarios` f
			where f.`codigo_funcionario` = fl.`codigo_funcionario` and
      fl.`codigo_linea` = l.`codigo_linea` and f.`cod_ciudad` in ($codTerritorio) and f.`cod_cargo`=1011 and 
      f.`estado`=1;";
$resp=mysql_query($sql);
echo "<select name='rpt_linea' class='texto' size='6'>";
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$nombre=$dat[1];
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>