<?php
header ( "Content-Type: text/html; charset=UTF-8" );

set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'"); 
$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$linea = $_POST['linea'];
$especialidad = $_POST['especialidad'];
$categoria = $_POST['categoria'];
$cantidad = $_POST['cantidad'];
$producto = $_POST['producto'];
$lineaMkt=$_POST['lineaMkt'];

// echo $ciclo." - ".$gestion." - ".$linea." - ".$especialidad." - ".$categoria." - ".$producto;

$sql_id = mysql_query("SELECT id from asignacion_mm_excel where ciclo = $ciclo and gestion = $gestion");
$id = mysql_result($sql_id, 0, 0);

$sql_update = mysql_query("UPDATE asignacion_mm_excel_detalle set cantidad = $cantidad where id = $id and especialidad = '$especialidad' and linea = '$linea' and categoria = '$categoria' and producto = '$producto' and linea_mkt='$lineaMkt'");
// echo("UPDATE asignacion_mm_excel_detalle set cantidad = $cantidad where id = $id and especialidad = $especialidad and linea = $linea and categoria = $categoria and producto = $producto ");
?>