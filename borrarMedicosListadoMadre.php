<?php
require("conexion.inc");

$ciclosExc="8,9,10";
$gestionExc="1008";

//ESTO ES PARA LOS RUTEROS NORMALES
$sqlRutero="select rc.`cod_rutero`, r.`cod_contacto` from `rutero_maestro` r, `rutero_maestro_cab` rc
		where r.`cod_rutero`=rc.`cod_rutero` and 
		rc.`codigo_ciclo` not in ($ciclosExc) and rc.`codigo_gestion` not in ($gestionExc)";
$respRutero=mysql_query($sqlRutero);

while($datRutero=mysql_fetch_array($respRutero)){
	$codRutero=$datRutero[0];
	$codContacto=$datRutero[1];
	
	$sqlDel="delete from `rutero_maestro_detalle` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
	
	$sqlDel="delete from `rutero_maestro` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from `rutero_maestro_cab` where cod_rutero='$codRutero'";
	$respDel=mysql_query($sqlDel);
}

echo "TERMINO RUTERO NORMAL<BR>";

//ESTO ES PARA LOS RUTEROS APROBADOS
$sqlRutero="select rc.`cod_rutero`, r.`cod_contacto` from `rutero_maestro_aprobado` r, `rutero_maestro_cab_aprobado` rc
		where r.`cod_rutero`=rc.`cod_rutero` and 
		rc.`codigo_ciclo` not in ($ciclosExc) and rc.`codigo_gestion` not in ($gestionExc)";
$respRutero=mysql_query($sqlRutero);

while($datRutero=mysql_fetch_array($respRutero)){
	$codRutero=$datRutero[0];
	$codContacto=$datRutero[1];
	
	$sqlDel="delete from `rutero_maestro_detalle_aprobado` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
	
	$sqlDel="delete from `rutero_maestro_aprobado` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);

	$sqlDel="delete from `rutero_maestro_cab_aprobado` where cod_rutero='$codRutero'";
	$respDel=mysql_query($sqlDel);
}

echo "TERMINO RUTERO APROBADO<BR>";

//ESTO ES PARA LAS COPIAS DE LOS RUTEROS
$sqlRutero="select r.`cod_contacto` from `rutero` r where r.`cod_ciclo` not in ($ciclosExc) and 
	r.`codigo_gestion` not in ($gestionExc)";
$respRutero=mysql_query($sqlRutero);

while($datRutero=mysql_fetch_array($respRutero)){
	$codContacto=$datRutero[0];
	
	$sqlDel="delete from `rutero_detalle` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
	
	$sqlDel="delete from `rutero` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
}
echo "TERMINO RUTERO SIMPLE<BR>";

//ESTO ES PARA LAS COPIAS DE LOS RUTEROS UTILIZADOS
$sqlRutero="select r.`cod_contacto` from `rutero_utilizado` r where r.`cod_ciclo` not in ($ciclosExc) and 
	r.`codigo_gestion` not in ($gestionExc)";
$respRutero=mysql_query($sqlRutero);

while($datRutero=mysql_fetch_array($respRutero)){
	$codContacto=$datRutero[0];
	
	$sqlDel="delete from `rutero_detalle_utilizado` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
	
	$sqlDel="delete from `rutero_utilizado` where cod_contacto='$codContacto'";
	$respDel=mysql_query($sqlDel);
}
echo "TERMINO RUTERO COPIA<BR>";




?>