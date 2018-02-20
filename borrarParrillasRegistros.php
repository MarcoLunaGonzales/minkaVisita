<?php
require("conexion.inc");

$ciclosExc="8,9,10";
$gestionExc="1008";

//ESTO ES PARA LA PARRILLA NORMAL
$sql="select p.`codigo_parrilla` from `parrilla` p where p.`cod_ciclo` not in ($ciclosExc)
	and p.`codigo_gestion` not in ($gestionExc)";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codParrilla=$dat[0];
	
	$sqlDel="delete from parrilla_detalle where codigo_parrilla='$codParrilla'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from parrilla where codigo_parrilla='$codParrilla'";
	$respDel=mysql_query($sqlDel);
}

echo "PARRILLA NORMAL TERMINADO<BR>";

//ESTO ES PARA LA PARRILLA ESPECIAL
$sql="select p.`codigo_parrilla_especial` from `parrilla_especial` p where p.`cod_ciclo` not in ($ciclosExc) and
p.`codigo_gestion` not in ($gestionExc)";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codParrilla=$dat[0];
	
	$sqlDel="delete from `parrilla_detalle_especial` where codigo_parrilla_especial='$codParrilla'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from parrilla_especial where codigo_parrilla_especial='$codParrilla'";
	$respDel=mysql_query($sqlDel);
}

echo "PARRILLA ESPECIAL TERMINADO<BR>";



//ESTO ES PARA EL REGISTRO DE VISITA
$sql="select p.`codigo_parrilla_especial` from `parrilla_especial` p where p.`cod_ciclo` not in ($ciclosExc) and
p.`codigo_gestion` not in ($gestionExc)";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codParrilla=$dat[0];
	
	$sqlDel="delete from `parrilla_detalle_especial` where codigo_parrilla_especial='$codParrilla'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from parrilla_especial where codigo_parrilla_especial='$codParrilla'";
	$respDel=mysql_query($sqlDel);
}

echo "REGISTRO VISITA TERMINADO<BR>";

$sqlDel="delete from registro_visita where codigo_ciclo not in ($ciclosExc) and 
	codigo_gestion not in ($gestionExc)";
$respDel=mysql_query($sqlDel);


?>