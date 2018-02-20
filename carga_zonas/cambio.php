<?php 

set_time_limit(0);
require '../conexion.inc';
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
    $cellIterator->setIterateOnlyExistingCells(false); 
    if (1 == $row->getRowIndex())
        continue; 
    $rowIndex = $row->getRowIndex();
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('C' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('D' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } 
    }

}

$mal = 1 ;
$cadena = "";
$codigos_malos = '';

foreach ($array_data as $hoja1 => $datos1) {
    $ruc = $datos1['A'];
    $nombre = $datos1['B'];
    $distrito = $datos1['C'];
    $zona = $datos1['D'];

    $sql_cod_zona = mysql_query("SELECT cod_ciudad,cod_dist,cod_zona from zonas where zona = '$zona'");
    $num_zonas = mysql_num_rows($sql_cod_zona);
    if($num_zonas == '' or $num_zonas == 0){
        $codigo_zona = $zona;
        $mal = $mal * 0;
        $codigos_malos .= $codigo_zona . ", ";
    }else{
        $cod_ciudad = mysql_result($sql_cod_zona, 0, 0);
        $cod_dist   = mysql_result($sql_cod_zona, 0, 1);
        $cod_zona   = mysql_result($sql_cod_zona, 0, 2);
        $codigo_zona = $cod_zona;
        $mal = $mal * 1;
    }
    $cadena .= $ruc . "," . $codigo_zona . ",";
}
if($mal == 0){
    $codigos_malos_subs = substr($codigos_malos, 0, -2);
    $codigos_malos_finales = explode(",", $codigos_malos_subs);
    $codigos_malos_finales_sd=array_unique($codigos_malos_finales);

    $cadena_mal = "";
    $cadena_mal .= '<h2>Zonas No Encontradas (No se cargara el archivo, verifique por favor)</h2>';
    $cadena_mal .= '<table border="1">';
    $cadena_mal .= '<tr>';
    $cadena_mal .= '<td><strong>Zona </strong></td>';
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
    $cadena_chunk = array_chunk($cadena_explode, 2);

    foreach ($cadena_chunk as $a => $b) {
        $sql_update = mysql_query("UPDATE direcciones_medicos set cod_zona = $b[1] where cod_med = $b[0]");
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

?>