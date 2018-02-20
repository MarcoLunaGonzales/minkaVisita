<?php

set_time_limit(0);
require("../../conexion.inc");

$datos = $_POST['datos'];
$datos = substr($datos, 0, -1);
$datos = explode("@", $datos);
mysql_query("TRUNCATE table productos_objetivo_cantidad");

foreach ($datos as $key ) {
    $key = explode("-",$key);
    mysql_query("INSERT into productos_objetivo_cantidad(cod_ciudad,cantidad) values($key[0],$key[1])");
}


?>