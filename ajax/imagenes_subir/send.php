<?php

set_time_limit(0);
require_once ('../../conexion.inc');

$codigo_material = $_REQUEST['codigo_material'];
$nombre_pic = $_REQUEST['nombre_pic'];


$sql = "update material_apoyo set url_imagen = '$nombre_pic' where codigo_material = $codigo_material";
//echo $sql;
$res = mysql_query($sql);


if ($res) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>