<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$nombre_rutero    = $_POST['nombre_rutero'];
$ciclo_gestion    = $_POST['ciclo_gestion'];
$global_visitador = $_POST['global_visitador'];
$global_linea     = 0;

$ciclo_gestion = explode("|", $ciclo_gestion);
$ciclo         = $ciclo_gestion[0];
$gestion       = $ciclo_gestion[1];

$sql_id = mysql_query("SELECT max(cod_rutero) from rutero_maestro_cab");
$id     = mysql_result($sql_id, 0, 0);
$id     = $id + 1;

$sql_inserta =  mysql_query("INSERT into rutero_maestro_cab values($id,'$nombre_rutero','$global_visitador',0,'$global_linea','$ciclo','$gestion','0000-00-00',1)");
?>
