<?php
require('conexion.inc');
$sql_medicos="select ap_pat_med, ap_mat_med, nom_med from medicos where cod_ciudad='116' order by ap_pat_med, ap_mat_med";
$resp_medicos=mysql_query($sql_medicos);
while($dat_medicos=mysql_fetch_array($resp_medicos))
{	$paterno=$dat_medicos[0];
	$materno=$dat_medicos[1];
	$nombres=$dat_medicos[2];
	echo "$paterno, $materno, $nombres<br>";
	
}
?>