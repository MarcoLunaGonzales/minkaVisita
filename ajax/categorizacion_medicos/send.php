<?php

set_time_limit(0);
require("../../conexion.inc");

$nombres = $_REQUEST['nombres'];
$direcciones = $_REQUEST['direcciones'];
$sexo = $_REQUEST['sexo'];
$edad = $_REQUEST['edad'];
$paci = $_REQUEST['paci'];
$prescriptiva = $_REQUEST['prescriptiva'];
$nivel = $_REQUEST['nivel'];
$consulta = $_REQUEST['consulta'];
$cod_medico = $_REQUEST['cod_medico'];

$nombres_final = substr($nombres, 0, -1);
$direcciones_final = substr($direcciones, 0, -1);

$nombres_final_explode = explode(',', $nombres_final);
$direcciones_final_explode = explode(',', $direcciones_final);

$total_valores = count($nombres_final_explode);


$sql_select = "select count(cod_med) as cod_med from categorizacion_medico where cod_med = $cod_medico";
$resp_sql_select = mysql_query($sql_select);
while ($row = mysql_fetch_assoc($resp_sql_select)) {
    $cont = $row['cod_med'];
}
if ($cont == 0) {
    $sql = "insert into categorizacion_medico (cod_med,sexo,edad,n_pacientes,tiene_preferencia,nivel,costo) values ($cod_medico,'$sexo',$edad,$paci,'$prescriptiva','$nivel',$consulta)";
} else {
    $sql = "update categorizacion_medico set sexo='$sexo' , edad = $edad,  n_pacientes = $paci , tiene_preferencia = '$prescriptiva' , nivel = '$nivel' ,costo = $consulta where cod_med = $cod_medico";
}
$res = mysql_query($sql);



$sql_select_farm = "select count(cod_med) as cod_med from farmacias_referencia_medico where cod_med= $cod_medico";
$resp_sql_select_farm = mysql_query($sql_select_farm);
while ($row_farm = mysql_fetch_assoc($resp_sql_select_farm)) {
    $cont_farm = $row_farm['cod_med'];
}


if ($cont_farm == 0) {
    for ($x = 0; $x < $total_valores; $x++) {
        $sql_farm = "insert into farmacias_referencia_medico (cod_med,nombre_farmacia,direccion_farmacia) values ($cod_medico,'$nombres_final_explode[$x]', '$direcciones_final_explode[$x]' )";
        $res_farm = mysql_query($sql_farm);
//        echo $sql_farm;
    }
} else {
    $sql_del = "delete from farmacias_referencia_medico where cod_med = $cod_medico ";
    $res_sql_del = mysql_query($sql_del);
    for ($x = 0; $x < $total_valores; $x++) {
        $sql_farm = "insert into farmacias_referencia_medico (cod_med,nombre_farmacia,direccion_farmacia) values ($cod_medico,'$nombres_final_explode[$x]', '$direcciones_final_explode[$x]' )";
        $res_farm = mysql_query($sql_farm);
//        echo $sql_farm;
    }
}
if ($res && $res_farm) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>