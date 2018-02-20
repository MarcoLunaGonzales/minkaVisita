<?php

require('conexion.inc');

$sql="select codigo, categoria, rpm from rpmcat";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$categoria=$dat[1];
	$rpm=$dat[2];
	
	$sqlUpd="update muestras_medicas set cod_categoria=$categoria, rpm=$rpm where codigo_muestra='$codigo'";
	$respUpd=mysql_query($sqlUpd);
	
	echo $sqlUpd;
}
?>