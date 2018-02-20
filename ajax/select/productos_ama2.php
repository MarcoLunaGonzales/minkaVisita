<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_gestion = $_GET['ciclo'];
if($ciclo_gestion==0){
	echo json_encode("<option value='0'>Escoga un ciclo por favor</option>");
}else{
	$ciclo_gestion_explode = explode("|", $ciclo_gestion);
	$ciclo_final = $ciclo_gestion_explode[0];
	$gestion_final = $ciclo_gestion_explode[1];


	$results = mysql_query("SELECT DISTINCT p.codigo_producto, CONCAT(m.descripcion,' ',m.presentacion) as nom from productos_objetivo_detalle p, muestras_medicas m, productos_objetivo_cabecera pc, productos_objetivo po where p.codigo_producto = m.codigo and pc.id = po.id_cabecera and po.id = p.id and pc.ciclo = $ciclo_final and pc.gestion = $gestion_final order by 2");
	while (is_resource($results) && $row = mysql_fetch_object($results)) {
	    $response .= "<option value='$row->codigo_producto'>" .  $row->nom . "</option>";
	}
	echo json_encode($response);	
}

?>