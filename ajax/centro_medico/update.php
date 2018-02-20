<?php

set_time_limit(0);
require("../../conexion.inc");

$codigo = $_REQUEST['codigo'];
$nombre = $_REQUEST['nombre'];
$direccion = $_REQUEST['direccion'];
$ciudad = $_REQUEST['ciudad'];

$sql_insert = mysql_query(" Update centros_medicos set nombre = '$nombre' , direccion = '$direccion' , cod_ciudad = $ciudad where cod_centro_medico = $codigo ");

if ($sql_insert) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>