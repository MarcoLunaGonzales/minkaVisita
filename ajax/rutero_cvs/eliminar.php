<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$global_visitador = $_POST['global_visitador'];
$cadena_eliminar = $_POST['cadena_eliminar'];
$cadena_eliminar = substr($cadena_eliminar, 0, -1);
$cadena_eliminar = explode(",", $cadena_eliminar);

foreach ($cadena_eliminar as $codigo_rutero) {
	$sql_rutero_maestro = mysql_query("SELECT cod_contacto from rutero_maestro where cod_rutero = '$codigo_rutero'");
	while($row_rutero_maestro = mysql_fetch_array($sql_rutero_maestro)){
		$cod_contacto = $row_rutero_maestro[0];
		$sql_eliminar_detalle = mysql_query("DELETE from rutero_maestro_detalle where cod_contacto = $cod_contacto");
	}
	$sql_eliminar_rutero = mysql_query("DELETE from rutero_maestro where cod_rutero = '$codigo_rutero' and cod_visitador = '$global_visitador'");
	$sql_eliminar_cabecera = mysql_query("DELETE from rutero_maestro_cab where cod_rutero = $codigo_rutero and cod_visitador = $global_visitador");
}

?>
