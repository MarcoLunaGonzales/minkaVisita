<?php

set_time_limit(0);
require_once ('../../baco/coneccion.php');

$codigo_hermes = $_REQUEST['codigo_hermes'];
$codigo_baco = $_REQUEST['codigo_baco'];


$sql = "update materiales set cod_hermes='$codigo_hermes' where cod_material = $codigo_baco";
$res = mssql_query($sql);


if ($res) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>