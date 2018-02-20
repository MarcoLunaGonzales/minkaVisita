<?php

set_time_limit(0);
require("../../conexion.inc");

$nombre = $_REQUEST['nombre'];
$direccion = $_REQUEST['direccion'];
$ciudad = $_REQUEST['ciudad'];

$sql_insert = mysql_query(" INSERT into centros_medicos (nombre,direccion,cod_ciudad) values ('$nombre','$direccion',$ciudad) ");

if ($sql_insert) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>