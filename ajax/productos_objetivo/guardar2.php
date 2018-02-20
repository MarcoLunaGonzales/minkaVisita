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
$territori = $_POST['ciudad'];

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

$sql_id_cabecera   = mysql_query("SELECT max(id) from po_p1");
$id_cabecera = mysql_result($sql_id_cabecera, 0, 0);
$id_cabecera = $id_cabecera + 1;

$sq_insert_cabecera = mysql_query("INSERT into po_p1 (id, ciclo, gestion, ciudad) values ($id_cabecera, $ciclo, $gestion, $territori)");
echo("INSERT into po_p1 (id, ciclo, gestion, ciudad) values ($id_cabecera, $ciclo, $gestion, $territori)");

$sql_id = mysql_query("SELECT max(id) from po_p1_detalle");
$id = mysql_result($sql_id, 0, 0);
$id = $id + 1;

$id_detalle = $id;
$cadena ='';
print_r($datos_chunk_1);
foreach ($datos_chunk_1 as $key => $value) {

    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[0],'$value[1]',$value[3],1)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[4],'$value[5]',$value[7],2)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[8],'$value[9]',$value[11],3)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[12],'$value[13]',$value[15],4)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[16],'$value[17]',$value[19],5)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[20],'$value[21]',$value[23],6)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[24],'$value[25]',$value[27],7)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[28],'$value[29]',$value[31],8)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[32],'$value[33]',$value[35],9)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[36],'$value[37]',$value[39],10)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[40],'$value[41]',$value[43],11)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[44],'$value[45]',$value[47],12)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[48],'$value[49]',$value[51],13)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[52],'$value[53]',$value[55],14)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[56],'$value[57]',$value[59],15)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[60],'$value[61]',$value[63],16)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[64],'$value[65]',$value[67],17)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[68],'$value[69]',$value[71],18)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[72],'$value[73]',$value[75],19)");
    mysql_query("INSERT into po_p1_detalle (id,visitador, producto, contactos, orden) VALUES ($id_cabecera,$value[76],'$value[77]',$value[79],20)");
    $id++;
}

?>