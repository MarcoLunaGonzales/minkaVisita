<?php 

set_time_limit(0);
require '../conexion.inc';
require 'coneccion.php';
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '1024M');

$sql_nombre_distrito_comparar = mysql_query("SELECT * from distritos");
while ($row_nombre_distrito_comparar = mysql_fetch_array($sql_nombre_distrito_comparar)) {
    $ciudad = $row_nombre_distrito_comparar[0];
    $distrito = $row_nombre_distrito_comparar[2];

    if($ciudad == 102){$ciudadd = 2;}
    if($ciudad == 104){$ciudadd = 8;}
    if($ciudad == 109){$ciudadd = 7;}
    if($ciudad == 113){$ciudadd = 1;}
    if($ciudad == 114){$ciudadd = 11;}
    if($ciudad == 116){$ciudadd = 3;}
    if($ciudad == 117){$ciudadd = 9;}
    if($ciudad == 118){$ciudadd = 5;}
    if($ciudad == 119){$ciudadd = 6;}
    if($ciudad == 120){$ciudadd = 4;}
    if($ciudad == 121){$ciudadd = 10;}

    $sql_cod_distrito_x_actualizar = mssql_query("SELECT cod_distrito from distritos where nombre_distrito = '$distrito' and cod_territorio = $ciudadd");
    $num_rows = mssql_num_rows($sql_cod_distrito_x_actualizar);
    if($num_rows > 0){
        $cod_distrito_x_actualizar = mssql_result($sql_cod_distrito_x_actualizar, 0, 0);
        $sql_update = mysql_query("UPDATE distritos set cod_dist = $cod_distrito_x_actualizar where descripcion = '$distrito' and cod_ciudad = $ciudad");
    }else{
        $distritos_no_encontrados .= $ciudad." - ".$ciudadd." - ".$distrito."<br /> ";
    }
}

echo $distritos_no_encontrados;

?>