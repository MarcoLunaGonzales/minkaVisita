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


	/*$txtSql="SELECT DISTINCT aspd.producto, CONCAT(m.descripcion,' ',m.presentacion) as nombre 
	from asignacion_productos_excel_detalle aspd, asignacion_productos_excel asp, muestras_medicas m 
	where asp.id = aspd.id and m.codigo = aspd.producto and asp.ciclo = $ciclo_final 
	and asp.gestion = $gestion_final and aspd.producto not in (select amae.codigo_mm from asignacion_ma_excel amae) ORDER BY 2";*/
	
	$txtSql="select distinct(p.cod_mm) as producto, CONCAT(m.descripcion,' ',m.presentacion) as nombre  from parrilla_personalizada p, muestras_medicas m 
		where p.cod_gestion=$gestion_final and p.cod_ciclo=$ciclo_final and m.codigo=p.cod_mm and 
		p.cod_mm not 
		in (select amae.codigo_mm from asignacion_ma_excel amae) order by 2";
	
	//echo $txtSql;
	
	$results = mysql_query($txtSql);
	while (is_resource($results) && $row = mysql_fetch_object($results)) {
	    $response .= "<option value='$row->producto'>" .  $row->nombre . "</option>";
	}
	echo json_encode($response);	
}

?>