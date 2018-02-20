<?php

set_time_limit(0);
require("../../conexion.inc");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$codigo_ma = $_POST['codigo_ma'];
$cab_pa_b = $_POST['cab_pa_b'];
$cantidad_a = $_POST['cantidad_a'];
$cantidad_b = $_POST['cantidad_b'];
$cantidad_c = $_POST['cantidad_c'];

// echo $ciclo . " - " . $gestion . " - " . $codigo_ma . " - " . $cab_pa_b . " - " . $cantidad_a . " - " . $cantidad_b . " - " . $cantidad_c . "\n"; 

$cab_pa_b_explode = explode("@", $cab_pa_b);
$linea_nom = $cab_pa_b_explode[0]." ".$cab_pa_b_explode[1];
$muestra_medica = $cab_pa_b_explode[2];

// echo $linea_nom." ".$muestra_medica;

$sql_select = mysql_query("SELECT id from asignacion_ma_excel where codigo_mm = '$muestra_medica' and ciclo = $ciclo and gestion = $gestion ");
$id = mysql_result($sql_select, 0, 0);

$sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$linea_nom' ");
$codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);

$sql_detalle = mysql_query("SELECT id, id_asignacion_ma from asignacion_ma_excel_detalle where codigo_l_visita = '$codigo_l_visita' and codigo_ma = '$codigo_ma' and dist_a = $cantidad_a and dist_b = $cantidad_b and dist_c = $cantidad_c  and espe_linea = '$linea_nom'");
$num_rows = mysql_num_rows($sql_detalle);
if($num_rows > 0){
	$id_p_b = mysql_result($sql_detalle, 0, 0);
	$id_asig_p_b = mysql_result($sql_detalle, 0, 1);

	if($id = $id_asig_p_b){

		$sql_delete = mysql_query("DELETE from asignacion_ma_excel_detalle where id = $id_p_b and id_asignacion_ma = $id_asig_p_b and codigo_l_visita = '$codigo_l_visita' and codigo_ma = '$codigo_ma' and dist_a = $cantidad_a and dist_b = $cantidad_b and dist_c = $cantidad_c  and espe_linea = '$linea_nom' ");

		$sql_delete2 = mysql_query("DELETE from asignacion_ma_excel_posiciones where id = $id_p_b");

	}
}


?>