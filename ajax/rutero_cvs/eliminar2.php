<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$codigos = $_POST['codigos'];
$visitador = $_POST['visitaddor'];
$codigos = substr($codigos, 0, -1);
$codigos = explode(",", $codigos);

foreach ($codigos as $codigo_rutero) {
	
	$sql_eliminar_rutero = mysql_query("DELETE from rutero_maestro where cod_contacto = '$codigo_rutero' and cod_visitador = '$global_visitador'");
	$sql_eliminar_cabecera = mysql_query("DELETE from rutero_maestro_detalle where cod_contacto = $codigo_rutero and cod_visitador = $global_visitador");
}

?>
