<?php

set_time_limit(0);
require("../../conexion.inc");

$codigos = $_REQUEST['id'];
$codigos_sub = substr($codigos, 0,-1);

$sql_estaod = mysql_query("update banco_muestras set estado = 2 where id in ($codigos_sub)");

echo json_encode("Medicos rechazados satisfactoriamente.");

?>