<?php
require("conexion.inc");

$sql="select c.`cod_cliente`, c.`nombre_cliente`, c.`dir_cliente`, c.`cod_area_empresa`, c.`cod_zona` from clientes c";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codCliente=$dat[0];
	$nombreCliente=$dat[1];
	$dirCliente=$dat[2];
	$codArea=$dat[3];
	$codZona=$dat[4];
	
	$sqlInsert1="insert into medicos (cod_med, ap_pat_med, cod_ciudad) values($codCliente, '$nombreCliente, $codArea')";
	echo "$sqlInsert1<br>";
	$respInsert1;		
}
?>