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

$sqlGestion="select g.codigo_gestion from gestiones g where g.estado='Activo'";
$respGestion=mysql_query($sqlGestion);
$codGestionActiva=mysql_result($respGestion,0,0);


$nombres_final = substr($nombres, 0, -1);
$direcciones_final = substr($direcciones, 0, -1);

$nombres_final_explode = explode(',', $nombres_final);
$direcciones_final_explode = explode(',', $direcciones_final);

$total_valores = count($nombres_final_explode);
  
$sql_select = "SELECT count(cod_med) as cod_med from banco_muestras where cod_med = $cod_medico";
$resp_sql_select = mysql_query($sql_select);
while ($row = mysql_fetch_assoc($resp_sql_select)) {
    $cont = $row['cod_med'];
}

$maximo = mysql_query("SELECT MAX(id) from banco_muestras");
$num_maximo = mysql_result($maximo, 0,0);
$date = date('Y-m-d');

if($num_maximo == 0 || $num_maximo == ''){
    $num_maximo_final = 1;
}else{
    $num_maximo_final = $num_maximo + 1;
}
if ($cont == 0) {
    $sql = "INSERT into banco_muestras (id,codigo_linea,cod_med,nro_visita, ciclo_inicio, ciclo_final,estado,gestion,fecha_registrado) values ($num_maximo_final,$linea,$cod_medico,$nro_visita,$ciclo1,$ciclo2,0,$codGestionActiva,'$date')";
} else {
    $sql = "UPDATE banco_muestras set nro_visita=$nro_visita, ciclo_inicio = $ciclo1, ciclo_final = $ciclo2 where cod_med = $cod_medico";
}
$res = mysql_query($sql);



$sql_select_farm = "SELECT count(cod_med) as cod_med from banco_muestras_detalle where cod_med= $cod_medico";
$resp_sql_select_farm = mysql_query($sql_select_farm);
while ($row_farm = mysql_fetch_assoc($resp_sql_select_farm)) {
    $cont_farm = $row_farm['cod_med'];
}


if ($cont_farm == 0) {
    for ($x = 0; $x < $total_valores; $x++) {
        $sql_farm = "INSERT into banco_muestras_detalle (id , cod_med,cod_muestra,cantidad) values ($num_maximo_final,$cod_medico,'$nombres_final_explode[$x]', $direcciones_final_explode[$x] )";
        $res_farm = mysql_query($sql_farm);
    }
} else {
    $sql_del = "DELETE from banco_muestras_detalle where cod_med = $cod_medico ";
    $res_sql_del = mysql_query($sql_del);
    for ($x = 0; $x < $total_valores; $x++) {
        $sql_farm = "INSERT into banco_muestras_detalle (id,cod_med,cod_muestra,cantidad) values ($num_maximo_final,$cod_medico,'$nombres_final_explode[$x]', $direcciones_final_explode[$x] )";
        $res_farm = mysql_query($sql_farm);
    }
}


if ($res && $res_farm) {
    echo json_encode("good");
} else {
    echo json_encode("bad");
}
?>