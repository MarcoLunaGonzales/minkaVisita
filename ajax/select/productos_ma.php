<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_gestion = $_GET['ciclo'];
if($ciclo_gestion==0){
	echo json_encode("<option value='0'>Escoga un ciclo por favor</option>");
}else{
	$ciclo_gestion_explode = explode("-", $ciclo_gestion);
	$ciclo_final = $ciclo_gestion_explode[0];
	$gestion_final = $ciclo_gestion_explode[1];


	$results = mysql_query("SELECT DISTINCT ad.codigo_ma, m.descripcion_material as nombre from asignacion_ma_excel am, asignacion_ma_excel_detalle ad, material_apoyo m where am.id = ad.id_asignacion_ma and m.codigo_material = ad.codigo_ma and am.ciclo = $ciclo_final and am.gestion = $gestion_final and ad.codigo_ma <> 0 ORDER BY 2");
	while (is_resource($results) && $row = mysql_fetch_object($results)) {
	    $response .= "<option value='$row->codigo_ma'>" .  $row->nombre . "</option>";
	}
	echo json_encode($response);	
}

?>