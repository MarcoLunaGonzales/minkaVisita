<?php  
set_time_limit(0);
require("../../conexion.inc");

$cod_med        = $_REQUEST['cod_med'];
$codigo_muestra = $_REQUEST['codigo_muestra'];
$frecuencia     = $_REQUEST['frecuencia'];
$posicionn      = $_REQUEST['posicionn'];
$va_ultima_pos  = $_REQUEST['va_ultima_pos'];
$cantidad_prod  = $_REQUEST['cantidad_prod'];


$sql_max = mysql_query("SELECT max(id) from muestras_agregadas");
$max_id  = mysql_result($sql_max, 0, 0);

if($max_id == '' or $max_id == 'null'){
	$id = 1;
}else{
	$id = $max_id + 1;
}

if($va_ultima_pos== 1){
	$posicion        = "todo";
	$ultima_posicion = 1;
}else{
	$posicion = $posicionn;
	$ultima_posicion = 0;
}
mysql_query("INSERT into muestras_agregadas (id,cod_med,codigo_muestra,frecuencia, posicion, cantidad,linea,ultima_posicion) values ($id,$cod_med,'$codigo_muestra','$frecuencia','$posicion','$cantidad_prod',1021,$ultima_posicion)");

$frecuencia = substr($frecuencia, 0, -1);
$frecuencia = explode("#", $frecuencia);

foreach ($frecuencia as $value) {
	mysql_query("INSERT into muestras_agregadas_frecuencia (id,frecuencia) values ($id,$value)");
}
$sql1= mysql_query("UPDATE muestras_agregadas_sugeridas set estado = 1 where muestra_mm = '$codigo_muestra' and cod_med = $cod_med ");

echo json_encode("Muestras Agregadas Satisfactoriamente");
?>