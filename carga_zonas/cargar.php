<?php 

error_reporting(0);
set_time_limit(0);
require '../conexion.inc';
require 'coneccion.php';
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '1024M');

$archivo = $_REQUEST["archivo"];

$date = date('Y-m-d');

$filename = dirname(__FILE__) . '/uploads/' . $archivo;

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);

$objPHPExcel->setActiveSheetIndex(0);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data = array();
$cadena = '';

foreach ($rowIterator as $row) {
	$cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; 
    $rowIndex = $row->getRowIndex();
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('C' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } 
    }

}

$mal = 1 ;
$cadena = "";
$codigos_malos = '';

// $sql_zona = mysql_query("SELECT max(cod_zona) from zonas");
// $cod_zona = mysql_result($sql_zona, 0, 0);
// if($cod_zona == '' or $cod_zona == 0){
//     $cod_zona = 1;
// }else{
//     $cod_zona = $cod_zona + 1;
// }

foreach ($array_data as $hoja1 => $datos1) {
    $ciudad = $datos1['A'];
    $distrito = $datos1['B'];
    $zona = $datos1['C'];

    $sql_distrito = mysql_query("SELECT cod_dist from distritos where descripcion = '$distrito' and cod_ciudad = $ciudad");
    $num_distritos = mysql_num_rows($sql_distrito);
    if($num_distritos == '' or $num_distritos == 0){
        $codigo_zona = $zona;
        $mal = $mal * 0;
        $codigos_malos .= $codigo_zona . ", ";
    }else{
        $codigo_distrito = mysql_result($sql_distrito, 0, 0);
        $sql_cod_zona = mssql_query("SELECT cod_zona from zonas where cod_distrito = $codigo_distrito and nombre_zona = '$zona'");
        $cod_zona = mssql_result($sql_cod_zona, 0, 0);
        $codigo_zona = $zona;
        $mal = $mal * 1;
    }
    $cadena .= $ciudad . "," . $codigo_distrito . "," . $cod_zona . "," . $codigo_zona . ",";
    // $cod_zona++;
}
if($mal == 0){
    $codigos_malos_subs = substr($codigos_malos, 0, -2);
    $codigos_malos_finales = explode(",", $codigos_malos_subs);
    $codigos_malos_finales_sd=array_unique($codigos_malos_finales);

    $cadena_mal = "";
    $cadena_mal .= '<h2>Distritos No Encontradas (No se cargara el archivo, verifique por favor)</h2>';
    $cadena_mal .= '<table border="1">';
    $cadena_mal .= '<tr>';
    $cadena_mal .= '<td><strong>Distrito </strong></td>';
    $cadena_mal .= '</tr>';
    foreach ($codigos_malos_finales_sd as $codigos_finales_f) {

        $cadena_mal .= '<tr>';
        $cadena_mal .= '<td>' . $codigos_finales_f . '</td>';
        $cadena_mal .= '</tr>';
    }
    $cadena_mal .= '</table>';
    $arr = array("cadena_mal" => $cadena_mal, "mensaje" => "lleno");
    echo json_encode($arr);
} else {

    $cadena_subs = substr($cadena, 0, -1);
    $cadena_explode = explode(",", $cadena_subs);
    $cadena_chunk = array_chunk($cadena_explode, 4);

    foreach ($cadena_chunk as $a => $b) {
        $query = 'INSERT into zonas (cod_ciudad,cod_dist, cod_zona, zona) VALUES (';
            foreach ($b as $c => $d) {
                $query .= "'". $d . "',";
            }
            $query = substr($query, 0, -1);
            $query .= ");";
            mysql_query($query);
    }


    $arr = array("cadena_bien" => "<h2>Datos ingresados satisfactoriamente!</h2>", "mensaje" => "vacio");
    echo json_encode($arr);

}

$dir = dirname(__FILE__) . '/uploads/';
$handle = opendir($dir);
while ($file = readdir($handle)) {
    if (is_file($dir . $file)) {
        unlink($dir . $file);
    }
}
