<?php

set_time_limit(0);
require("../../conexion.inc");

$codigo = $_REQUEST['codigo'];


$sql_insert = mysql_query(" DELETE from centros_medicos where cod_centro_medico = $codigo ");

if ($sql_insert) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>