<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$ciclo_ini = $_GET['ciclo_ini'];
$ciclo_para = $_GET['ciclo_para'];
$date = date('Y-m-d');

$ciclo_de_f = explode("-", $ciclo_ini);
$ciclo_de_final = $ciclo_de_f[0];
$gestion_de_final = $ciclo_de_f[1];

$ciclo_para_f = explode("-", $ciclo_para);
$ciclo_para_final = $ciclo_para_f[0];
$gestion_para_final = $ciclo_para_f[1];



$sql_id = mysql_query("SELECT max(id) from asignacion_mm_excel");
$id = mysql_result($sql_id, 0, 0);
if($id == '' or $id == 0){
	$id = 1;
}else{
	$id = $id + 1;
}
$sql_cabecera = mysql_query("INSERT into asignacion_mm_excel (id, ciclo, gestion, fecha) values ($id,$ciclo_para_final,$gestion_para_final,'$date')");
// echo("INSERT into asignacion_mm_excel (id, ciclo, gestion, fecha) values ($id,$ciclo_para_final,$gestion_para_final,'$date')");

$results = mysql_query("SELECT ad.especialidad,ad.linea,ad.categoria, ad.cantidad, ad.producto, ad.posicion from asignacion_mm_excel ac, asignacion_mm_excel_detalle ad where ad.id= ac.id and ac.ciclo = $ciclo_de_final and ac.gestion = $gestion_de_final ");
while($row_i =  mysql_fetch_array($results)){
	mysql_query("INSERT into asignacion_mm_excel_detalle (id,especialidad,linea,categoria,cantidad,producto,posicion) values ($id,'$row_i[0]','$row_i[1]','$row_i[2]','$row_i[3]','$row_i[4]','$row_i[5]')");
}

echo json_encode("Datos replicados satisfactoriamente.");
?>