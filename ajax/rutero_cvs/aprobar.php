<?php  
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$cod_rutero = $_POST['cadena_eliminar'];
$visitador  = $_POST['global_visitador'];

$sql_pre = "UPDATE rutero_maestro_cab set estado_aprobado='0' where cod_visitador='$visitador' and cvs = 1";
$resp_pre = mysql_query($sql_pre);
$sql_aprueba = "UPDATE rutero_maestro_cab set estado_aprobado='2' where cod_visitador='$visitador' and cod_rutero='$cod_rutero'";
$resp_aprueba = mysql_query($sql_aprueba);
?>