<?php
require('conexion.inc');
$codGestion=$_GET['codGestion'];
$sql_ciclo="select distinct(cod_ciclo), estado 
						from ciclos 
						where codigo_gestion='$codGestion' order by cod_ciclo desc";
$resp_ciclo=mysql_query($sql_ciclo);
echo "<select name='ciclo_rpt' class='texto' size='5' multiple>";
while($datos_ciclo=mysql_fetch_array($resp_ciclo))
{	$cod_ciclo_rpt=$datos_ciclo[0];$estado_ciclo_rpt=$datos_ciclo[1];
	echo "<option value='$cod_ciclo_rpt'>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
}
	echo "</select>";
?>