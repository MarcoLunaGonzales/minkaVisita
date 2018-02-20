<?php

set_time_limit(0);
require("../../conexion.inc");

$nombres = $_REQUEST['nombres'];
$direcciones = $_REQUEST['direcciones'];
$cod_medico = $_REQUEST['cod_medico'];
$linea = $_REQUEST['linea'];
$nro_visita = $_REQUEST['numero'];
$ciclo1 = $_REQUEST['ciclo1'];
$ciclo2 = $_REQUEST['ciclo2'];
$cod_visitador = $_REQUEST['global_usuario'];
$date = date('Y-m-d');

$nombres_final = substr($nombres, 0, -1);
$direcciones_final = substr($direcciones, 0, -1);

$nombres_final_explode = explode(',', $nombres_final);
$direcciones_final_explode = explode(',', $direcciones_final);

$total_valores = count($nombres_final_explode);

$sql_id_principal = mysql_query("SELECT id from banco_muestras where cod_med = $cod_medico");
$id_principal     = mysql_result($sql_id_principal, 0, 0);

$sql_update_desaprobar = mysql_query("UPDATE banco_muestras set estado = 0 where id = $id_principal and cod_med = $cod_medico");

$sql = "UPDATE banco_muestras set nro_visita = $nro_visita, ciclo_inicio = $ciclo1, ciclo_final = $ciclo2, fecha_registrado = '$date' where cod_med = $cod_medico and id = $id_principal";
$res = mysql_query($sql);


$sql_del = "DELETE from banco_muestras_detalle where cod_med = $cod_medico and id = $id_principal";
$res_sql_del = mysql_query($sql_del);
for ($x = 0; $x < $total_valores; $x++) {
    $sql_farm = "INSERT into banco_muestras_detalle (id,cod_med,cod_muestra,cantidad) values ($id_principal,$cod_medico,'$nombres_final_explode[$x]', $direcciones_final_explode[$x] )";
    $res_farm = mysql_query($sql_farm);
}

$sql_visitador = mysql_query("SELECT * from banco_muestra_cantidad_visitador where id_for = $id_principal and cod_medico = $cod_medico");
$num_visitadores = mysql_num_rows($sql_visitador);

if($num_visitadores > 0){
    $sql_del_x_visitador = mysql_query("DELETE from banco_muestra_cantidad_visitador where id_for = $id_principal and cod_medico = $cod_medico");
}
if ($res && $res_farm) {
    $arr = array('mensaje' => 'good' , 'medico' => $cod_medico, 'visitador' => $cod_visitador);
    echo json_encode($arr);
} else {
    $arr = array('mensaje' => 'bad');
    echo json_encode($arr);
}
?>