<?php
require('conexion.inc');
$codGestion=$_GET['codGestion'];
$codCiclo=$_GET['codCiclo'];

$sql_ciclo="select fecha_ini, fecha_fin from ciclos 
	where codigo_gestion='$codGestion' and cod_ciclo='$codCiclo'";
$resp_ciclo=mysql_query($sql_ciclo);
$fechaIni=mysql_result($resp_ciclo,0,0);
$fechaFin=mysql_result($resp_ciclo,0,1);

echo "<input type='date' name='fecha_rpt' min='$fechaIni' max='$fechaFin' value='$fechaIni' class='texto'>";

?>