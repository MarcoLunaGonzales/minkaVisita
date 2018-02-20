<?php
require('conexion.inc');
$sql="select codigo, principio from principios";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$principio=$dat[1];	
	$sqlAct="update muestras_medicas set principio_activo='$principio' where codigo='$codigo'";
	$respAct=mysql_query($sqlAct);

}


?>