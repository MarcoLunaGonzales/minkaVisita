<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena  = $_REQUEST['cadena'];
$cod_med = $_REQUEST['medico'];
$cod_lin = $_REQUEST['linea'];
$cadena  = substr($cadena, 0, -1);
$cadena  = explode(",", $cadena);

foreach ($cadena as $value) {
	mysql_query("INSERT into muestras_negadas values ('$cod_med', '$value', '$cod_lin')");
}

$arr = array("mensaje" => "Producto Quitado Satisfactoriamente");
echo json_encode($arr);
?>
