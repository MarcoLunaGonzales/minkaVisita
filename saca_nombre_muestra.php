<?php
function saca_nombre_muestra($codigo)
{	$sql="select descripcion from muestras_medicas where codigo='$codigo'";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$nombre_muestra=$dat[0];
	return($nombre_muestra);
}

function nombreGestion($codigo)
{	$sql="select g.`nombre_gestion` from `gestiones` g where g.`codigo_gestion`='$codigo'";
	$resp=mysql_query($sql);
	$nombre=mysql_result($resp,0,0);
	return($nombre);
}

?>