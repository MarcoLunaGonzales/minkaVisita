<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$date = date('Y-m-d');

$ruteros = $_GET['ruteros'];
$ciclo = $_GET['ciclo'];
$linea = $_GET['linea'];

$ciclos = explode("-", $ciclo);
$ciclos_finales = $ciclos[0];
$gestion = $ciclos[1];
foreach ($ruteros as $rutero) {
	$ruteros_finales .= $rutero . ",";
}
$ruteros_finales_sub = substr($ruteros_finales, 0, -1);
$ruteros_finales_sub_explode = explode(",", $ruteros_finales_sub);

foreach ($ruteros_finales_sub_explode as $cod_rutero) {

	$sql_cod_funcionario = mysql_query("SELECT cod_visitador from rutero_maestro_cab where cod_rutero = $cod_rutero");
	$cod_funcionario = mysql_result($sql_cod_funcionario, 0, 0);

	$sql_update = mysql_query("UPDATE rutero_maestro_cab set estado_aprobado = '0' where cod_visitador = '$cod_funcionario' and cod_rutero = $cod_rutero");
}


echo json_encode("Ruteros Rechazados satisfactoriamente.");
?>