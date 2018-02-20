<?php

set_time_limit(0);
require_once ('../../conexion.inc');
$ciclo = $_GET['ciclo'];
$codigo_gestion = $_GET['gestion'];
$fecha =  date("Y-m-d");
$estado = 1; // 1 ciclo cerrado, 0 ciclo abierto
$sql_id = mysql_query("select count(id) from lineas_visitadores_estados");
$num_id = mysql_num_rows($sql_id);
if ($num_id == 0) {
    $id = 1;
} else {
    $id_a = mysql_result($sql_id, 0,0);
    $id = $id_a+1;
}

$sql_insert = mysql_query("insert into lineas_visitadores_estados values($id,$ciclo,$codigo_gestion,'$fecha',$estado)");

if($sql_insert){
    echo json_encode("Ciclo Cerrado");
}else{
    echo json_encode("No se cerro adecuadamente el ciclo intentelo denuevo");
}

?>