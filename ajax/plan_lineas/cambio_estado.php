<?php

set_time_limit(0);
require("../../conexion.inc");
$id = $_GET['id'];

mysql_query("update plan_linea_cab set estado = 0");

$sql_1 = mysql_query("update plan_linea_cab set estado = 1 where id = $id");

//if($sql_1){
////    echo json_encode("Datos actualizado Satisfactoriamente");
//}else{
////    echo json_encode("Los datos no pudieron actualizarse. Int&eacute;ntelo de nuevo por favor");
//}

?>