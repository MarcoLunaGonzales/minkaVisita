<?php

set_time_limit(0);
require("../../conexion.inc");

// $codigos_funcioanrioss = $_POST['codigos_funcioanrioss'];
$datos     = $_POST['datos'];
$relegados = $_POST['relegados'];
$ciclo     = $_POST['ciclo'];
$gestion   = $_POST['gestion'];
$fecha     = $_POST['fecha'];
$desde     = $_POST['desde'];
$hasta     = $_POST['cuantos'];

$fecha_explode = explode("/", $fecha);
$mes = $fecha_explode[0];
$anio = $fecha_explode[2];

$hasta_chunk = ($hasta*2)*2;
$datos_11 = substr($datos, 0, -1);
$datos_explode = explode(",", $datos_11);
$datos_chunk_1 = array_chunk($datos_explode, $hasta_chunk);

$relegados = substr($relegados, 0, -1);
$relegados = explode(",",$relegados);

$sql_mes_ini = mysql_query("SELECT DISTINCT fecha_ini from ciclos where cod_ciclo = $ciclo and codigo_gestion = $gestion");
$mes_ini = mysql_result($sql_mes_ini, 0, 0);
$mes_ini = explode("-", $mes_ini);

$sql_id_cabecera   = mysql_query("SELECT max(id) from productos_objetivo_cabecera");
$id_cabecera = mysql_result($sql_id_cabecera, 0, 0);
$id_cabecera = $id_cabecera + 1;

$sq_insert_cabecera = mysql_query("INSERT into productos_objetivo_cabecera (id, ciclo, gestion, mes, anio, mes_inicio, anio_inicio) values ($id_cabecera, $ciclo, $gestion, $mes, $anio, $mes_ini[1], $mes_ini[0])");

$sql_id = mysql_query("SELECT max(id) from productos_objetivo");
$id = mysql_result($sql_id, 0, 0);
$id = $id + 1;

$id_detalle = $id;
$cadena ='';
foreach ($datos_chunk_1 as $key => $value) {
    // $unique = array_keys(array_flip($value));
    // print_r($value);
    $query = mysql_query("INSERT into productos_objetivo (id, codigo_funcionario, id_cabecera) VALUES ($id, $value[0], $id_cabecera)");

    if($hasta == 3){
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[1]',$value[3],1)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[5]',$value[7],2)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[9]',$value[11],3)");
    }
    if($hasta == 4){
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[1]',$value[3],1)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[5]',$value[7],2)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[9]',$value[11],3)");   
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[13]',$value[15],4)");   
    }
    if($hasta == 5){
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[1]',$value[3],1)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[5]',$value[7],2)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[9]',$value[11],3)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[13]',$value[15],4)");
        mysql_query("INSERT into productos_objetivo_detalle (id,codigo_producto,cantidad_distribuida,orden) VALUES ($id,'$value[17]',$value[19],5)");
    }
    $id++;
}

foreach ($relegados as $key => $value) {
    $value_relegados = explode("@", $value);
    if($value == ''){

    }else{
        mysql_query("INSERT into productos_objetivo_relegados(id_cabecera, cod_muestra_medica) values ($id_cabecera, '$value_relegados[1]')");
    }
}
?>