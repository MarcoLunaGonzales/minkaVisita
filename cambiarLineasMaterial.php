<?php
require('conexion.inc');
$sql="select codigo, linea from material_bk";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codigo=$dat[0];
	$linea=$dat[1];
	$sql_modi="update material_apoyo set codigo_linea=$linea where codigo_material=$codigo";
	echo $sql_modi;
	$resp_modi=mysql_query($sql_modi);
	
}

?>