<?php

include('../../conexion.inc'); 
$ciudad = $_GET['ciudad'];
$coordenadas = $_GET['coordenadas'];

$coordenadas_explode = explode("@", $coordenadas);

print_r($coordenadas_explode);

$sql = mysql_query(" insert into coordenadas (cod_ciudad, latitud, longitud, color, tipo_cordenada) values ($ciudad,0,0,0,0) ");


$arr = array("ciudad" => $ciudad, "coordenadas" => $coordenadas);
echo json_encode($arr);
?>