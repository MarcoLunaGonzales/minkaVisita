<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_POST['cadena'];
$ciclo_gestion = $_POST['ciclo'];
$fecha = $_POST['fecha'];
$territorios = $_POST['territorios'];

$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo = $ciclo_gestion_explode[0];
$gestion = $ciclo_gestion_explode[1];
// echo $cadena." ".$ciclo." ".$fecha;

$sql_id = mysql_query("SELECT max(id) from asignacion_de_prodcutos");
$id = mysql_result($sql_id, 0, 0);
if($id == '' or $id == 0){
    $id = 1;
}else{
    $id = $id + 1;
}

$territorios_explode = explode(",", $territorios);

$cadena_sub = substr($cadena, 0, -1);
$cadena_explode = explode(",", $cadena_sub);
$ciudades_temp = '';
$posicion = 1;
$linea_temp = ''; 

// echo $cadena;
// foreach ($territorios_explode as $ciudades) {

foreach ($cadena_explode as $cadenas) {
    $cadenas_explode = explode("@", $cadenas);
    if($ciudades_temp == $cadenas_explode[0]){

    }else{
        $id++;
        $sql_cabecera = mysql_query("INSERT into asignacion_de_prodcutos (id, ciclo, gestion, fecha_creacion, ciudad) values ($id,$ciclo,$gestion,'$fecha',$cadenas_explode[0])");
    }
    if($linea_temp == $cadenas_explode[1]){

    }else{
        $posicion = 1;
    }
    $sql_cuerpo = mysql_query("INSERT into asignacion_de_porducto_detalle (id,linea,producto,posicion,cantidad) values ($id,$cadenas_explode[1],'$cadenas_explode[3]',$posicion,0)");
    $posicion++;
    $ciudades_temp = $cadenas_explode[0];
    $linea_temp = $cadenas_explode[1];
}

?>