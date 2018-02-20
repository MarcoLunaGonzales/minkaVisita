<?php
require("conexion.inc");
$sql="select cod_med, ap_pat_med, ap_mat_med, nom_med from medicos";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$paterno=$dat[1];
	$materno=$dat[2];
	$nombres=$dat[3];
	$paterno=strtoupper($paterno);
	$materno=strtoupper($materno);
	$nombres=strtoupper($nombres);
	//echo "$codigo $paterno $materno $nombres <br>";
	$sql_update="update medicos set ap_pat_med='$paterno', ap_mat_med='$materno', nom_med='$nombres' where cod_med='$codigo'";
	echo $sql_update;
	$resp_update=mysql_query($sql_update);
}

?>