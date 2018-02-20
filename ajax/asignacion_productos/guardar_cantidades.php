<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_POST['cadena'];
$cadena_sub = substr($cadena, 0, -1);
$cadena_explode = explode(",", $cadena_sub);
foreach ($cadena_explode as $cadenas) {
    $cadenas_explode = explode("-", $cadenas);
    $sql_cuerpo = mysql_query("UPDATE asignacion_de_porducto_detalle set cantidad = $cadenas_explode[4] where id = $cadenas_explode[0] and linea = $cadenas_explode[1] and producto = '$cadenas_explode[2]' and posicion = $cadenas_explode[3]");
}

?>