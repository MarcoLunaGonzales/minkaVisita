<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_ini = $_GET['ciclo_ini'];
$territorios = $_GET['territorios'];

$ciuades_finales = '';

// foreach ($territorios as $ciudades) {
// 	$ciuades_finales .= $ciudades.",";
// }

// $ciuades_finales_sub = substr($ciuades_finales, 0, -1);
$ciclos = explode("-", $ciclo_ini);
$ciclos_finales = $ciclos[0];
$gestion = $ciclos[1];

$results = mysql_query("SELECT DISTINCT lv.codigo_l_visita ,lv.nom_orden from lineas_visita lv, asignacion_de_porducto_detalle a, asignacion_de_prodcutos ac where lv.codigo_l_visita = a.linea and a.id = ac.id and ac.ciclo = $ciclos_finales and ac.gestion = $gestion and ac.ciudad = $territorios ");
while (is_resource($results) && $row = mysql_fetch_object($results)) {
    $response .= "<option value='$row->codigo_l_visita'>" .  $row->nom_orden . "</option>";
}
echo json_encode($response);
?>