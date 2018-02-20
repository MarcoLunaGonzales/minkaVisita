<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_ini = $_GET['ciclo_ini'];
$territorio_ini = $_GET['territorio_ini'];
$especialidad = $_GET['especialidad'];
$territorio_para = $_GET['territorio_para'];
$ciclo_para = $_GET['ciclo_para'];
$date = date('Y-m-d');

$ciclo_de_f = explode("-", $ciclo_ini);
$ciclo_de_final = $ciclo_de_f[0];
$gestion_de_final = $ciclo_de_f[1];

$ciclo_para_f = explode("-", $ciclo_para);
$ciclo_para_final = $ciclo_para_f[0];
$gestion_para_final = $ciclo_para_f[1];



foreach ($especialidad as $especialidades) {
	$especialidades_finales .= "'".$especialidades . "',";
}
$especialidades_finales_sub = substr($especialidades_finales, 0, -1);

foreach ($territorio_para as $ciudades_finales) {
	$sql_id = mysql_query("SELECT max(id) from asignacion_de_prodcutos");
	$id = mysql_result($sql_id, 0, 0);
	if($id == '' or $id == 0){
		$id = 1;
	}else{
		$id = $id + 1;
	}
	$sql_cabecera = mysql_query("INSERT into asignacion_de_prodcutos (id, ciclo, gestion, fecha_creacion, ciudad) values ($id,$ciclo_para_final,$gestion_para_final,'$date',$ciudades_finales)");
	$results = mysql_query("SELECT ad.linea,ad.producto,ad.posicion from asignacion_de_prodcutos ac, asignacion_de_porducto_detalle ad where ad.id= ac.id and ac.ciclo = $ciclo_de_final and ac.gestion = $gestion_de_final and ac.ciudad = $territorio_ini and ad.linea in ($especialidades_finales_sub)");

	while($row_i =  mysql_fetch_array($results)){
    	mysql_query("INSERT into asignacion_de_porducto_detalle (id,linea,producto,posicion) values ($id,$row_i[0],'$row_i[1]',$row_i[2])");
	}
}



echo json_encode("Datos replicados satisfactoriamente.");
?>