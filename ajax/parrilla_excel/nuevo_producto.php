<?php

set_time_limit(0);
require("../../conexion.inc");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$linea = $_POST['linea'];
$especialidad = $_POST['especialidad'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];
$producto = $_POST['producto'];

// echo $ciclo." - ".$gestion." - ".$linea." - ".$especialidad." - ".$categoria." - ".$producto;

$sql_id = mysql_query("SELECT id from asignacion_mm_excel where ciclo = $ciclo and gestion = $gestion");
$id = mysql_result($sql_id, 0, 0);

$sql_posicion = mysql_query("SELECT MAX(ad.posicion) from asignacion_mm_excel_detalle ad, asignacion_mm_excel a where a.id = ad.id and a.ciclo = $ciclo and a.gestion = $gestion");
$posicion = mysql_result($sql_posicion, 0, 0);
$posicion = $posicion + 1;

$txtInsert="INSERT into asignacion_mm_excel_detalle (id,especialidad,linea,categoria,cantidad,producto,posicion, linea_mkt) 
values ($id,'$especialidad',0,'$categoria','$cantidad','$producto','$posicion','$linea')"; 
//echo $txtInsert;
$sql_inserta = mysql_query($txtInsert);
// echo("INSERT into asignacion_mm_excel_detalle (id,especialidad,linea,categoria,cantidad,producto) values ($id,'$especialidad','$linea','$categoria','$cantidad','$producto')");
?>