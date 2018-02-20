<?php 

error_reporting(0);
set_time_limit(0);
require '../../conexion.inc';
require_once '../../lib/excel/PHPExcel.php';
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



foreach ($array_data as $hoja1 => $datos1) {
    $codigo_especialidad = $datos1['A'];
    $codigo_muestra_medi = $datos1['B'];
    $codigo_linea        = $datos1['C'];

    
    $sql_cod_muestra   = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) = '$codigo_muestra_medi'");
    $num_cod_muestra   = mysql_num_rows($sql_cod_muestra);
    if($num_cod_muestra == '' or $num_cod_muestra == 0){
        $mal = $mal * 0;
        $codigos_malos .= $codigo_muestra_medi . ", ";
    }else{
        $codigo_muestra = mysql_result($sql_cod_muestra, 0, 0);
        $mal = $mal * 1;
    }
    $cadena .= $codigo_especialidad . "," . $codigo_muestra . "," . $codigo_linea . ",";
}

if($mal == 0){
    $codigos_malos_subs = substr($codigos_malos, 0, -2);
    $codigos_malos_finales = explode(",", $codigos_malos_subs);
    $codigos_malos_finales_sd=array_unique($codigos_malos_finales);

    $cadena_mal  = "";
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
    $cadena_chunk = array_chunk($cadena_explode, 3);

    foreach ($cadena_chunk as $a => $b) {
        $query = 'INSERT into producto_especialidad (cod_especialidad, codigo_mm, codigo_linea) VALUES (';
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
