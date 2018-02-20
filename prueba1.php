<?php
require('conexion.inc');
$sql="select codigo, nombre, principio from mm1";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$nombre=$dat[1];
	$principio=$dat[2];
	
	echo "$codigo $nombre $principio <br>";
	
	$sqlUpd="update muestras_medicas set principio_activo='$principio' where codigo='$codigo'";
	$respUpd=mysql_query($sqlUpd);
	
}
?>