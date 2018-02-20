<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_REQUEST['codigo'];

$sql_rechazado = mysql_query("update medicos set estado_registro = 4 where cod_med = $cadena");

if ($sql_rechazado) {
    $arr = array("mensaje" => 'bien');
    echo json_encode($arr);
} else {
    $arr = array("mensaje" => 'mal');
    echo json_encode($arr);
}
?>