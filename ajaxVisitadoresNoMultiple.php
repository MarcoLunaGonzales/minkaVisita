<?php
require('conexion.inc');
$codTerritorio=$_GET['codTerritorio'];
$sql_visitador="select distinct(f.codigo_funcionario), f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.codigo_funcionario=fl.codigo_funcionario 
	and f.cod_cargo='1011' and f.estado=1 and f.cod_ciudad in ($codTerritorio) 
	and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno";
$resp_visitador=mysql_query($sql_visitador);
echo "<select name='rpt_visitador' class='texto' size='6'>";
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codigo_visitador=$dat_visitador[0];
	$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
	echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
}
echo "</select>";
?>