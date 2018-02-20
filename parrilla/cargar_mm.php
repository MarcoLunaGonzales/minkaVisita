<?php 

set_time_limit(0);
require '../conexion.inc';
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '1024M');

$archivo = $_REQUEST["archivo"];
$ciclo_gestion = $_REQUEST["ciclo_gestion"];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$date = date('Y-m-d');

$filename = dirname(__FILE__) . '/uploads/' . $archivo;

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);

$objPHPExcel->setActiveSheetIndex(0);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data = array();
$producto = '';
$cadena = "";
$codigos_malos = '';
$mal = 1 ;

foreach ($rowIterator as $row) {
	$cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex() or 2 == $row->getRowIndex() or 3 == $row->getRowIndex() or 4 == $row->getRowIndex())
        continue; //skip first row
    $rowIndex = $row->getRowIndex();
    // echo $rowIndex . " - ";
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '',
        'I' => '', 'J' => '', 'K' => '', 'L' => '', 'M' => '', 'N' => '', 'O' => '', 'P' => '', 'Q' => '', 'R' => '', 'S' => '', 
        'T' => '', 'U' => '', 'V' => '', 'W' => '', 'X' => '', 'Y' => '', 'Z' => '', 'AA' => '', 'AB' => '', 'AC' => '', 'AD' => '', 'AE' => '', 'AF' => '', 'AG' => '', 'AH' => '', 'AI' => '', 'AJ' => '', 'AK' => '', 'AL' => '', 'AM' => '', 'AN' => '', 'AO' => '', 'AP' => '', 'AQ' => '', 'AR' => '', 'AS' => '', 'AT' => '', 'AU' => '', 'AV' => '', 'AW' => '', 'AX' => '', 'AY' => '', 'AZ' => '', 'BA' => '', 'BB' => '', 'BC' => '', 'BD' => '', 'BE' => '', 'BF' => '', 'BG' => '', 'BH' => '','BI' => '', 'BJ' => '', 'BK' => '', 'BL' => '', 'BM' => '', 'BN' => '', 'BO' => '', 'BP' => '', 'BQ' => '', 'BR' => '', 'BS' => '', 'BT' => '', 'BU' => '', 'BV' => '', 'BW' => '', 'BX' => '', 'BY' => '', 'BZ' => '', 
        'CA' => '', 'CB' => '', 'CC' => '', 'CD' => '', 'CE' => '', 'CF' => '', 'CG' => '', 'CH' => '','CI' => '', 'CJ' => '', 
        'CK' => '', 'CL' => '', 'CM' => '', 'CN' => '', 'CO' => '', 'CP' => '', 'CQ' => '', 'CR' => '', 'CS' => '', 
        'CT' => '', 'CU' => '', 'CV' => '', 'CW' => '', 'CX' => '', 'CY' => '', 'CZ' => '', 
        'DA' => '', 'DB' => '', 'DC' => '', 'DD' => '', 'DE' => '', 'DF' => '', 'DG' => '', 'DH' => '','DI' => '', 'DJ' => '', 
        'DK' => '', 'DL' => '', 'DM' => '', 'DN' => '', 'DO' => '', 'DP' => '', 'DQ' => '', 'DR' => '', 'DS' => '', 
        'DT' => '', 'DU' => '', 'DV' => '', 'DW' => '', 'DX' => '', 'DY' => '', 'DZ' => '', 
        'EA' => '', 'EB' => '', 'EC' => '', 'ED' => '', 'EE' => '', 'EF' => '', 'EG' => '', 'EH' => '','EI' => '', 'EJ' => '', 
        'EK' => '', 'EL' => '', 'EM' => '', 'EN' => '', 'EO' => '', 'EP' => '', 'EQ' => '', 'ER' => '', 'ES' => '', 
        'ET' => '', 'EU' => '', 'EV' => '', 'EW' => '', 'EX' => '', 'EY' => '', 'EZ' => '',
        'FA' => '', 'FB' => '', 'FC' => '', 'FD' => '', 'FE' => '', 'FF' => '', 'FG' => '', 'FH' => '','FI' => '', 'FJ' => '', 
        'FK' => '', 'FL' => '', 'FM' => '', 'FN' => '', 'FO' => '', 'FP' => '', 'FQ' => '', 'FR' => '', 'FS' => '', 
        'FT' => '', 'FU' => '', 'FV' => '', 'FW' => '', 'FX' => '', 'FY' => '', 'FZ' => '', 
        'GA' => '', 'GB' => '', 'GC' => '', 'GD' => '', 'GE' => '', 'GF' => '', 'GG' => '', 'GH' => '','GI' => '', 'GJ' => '', 
        'GK' => '', 'GL' => '', 'GM' => '', 'GN' => '', 'GO' => '', 'GP' => '', 'GQ' => '', 'GR' => '', 'GS' => '', 'GT' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('C' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('D' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('E' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('F' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('G' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('H' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('I' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('J' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('K' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('L' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('M' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('N' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('O' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('P' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('Q' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('R' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('S' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('T' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('U' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('V' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('W' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('X' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('Y' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('Z' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('AZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('BZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('CZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('DZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('ED' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('ER' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('ES' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('ET' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('EZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FU' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FV' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FW' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FX' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FY' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('FZ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GA' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GB' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GC' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GD' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GE' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GF' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GG' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GH' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GI' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GJ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GK' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GL' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GM' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GN' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GO' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GP' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GQ' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GR' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GS' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('GT' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } 
    }

}

$cabecera = array('A' => 'PRODUCTOS', 'B' => 'CAR,A,1-3', 'C' => 'CAR,B,1-3', 'D' => 'CAR,C,1-3', 'E' => 'CAR,A,2-3', 'F' => 'CAR,B,2-3', 'G' => 'CAR,C,2-3', 'H' => 'CAR,A,3-3', 'I' => 'CAR,B,3-3', 'J' => 'CAR,C,3-3',
    'K' => 'CAR,A,1-2', 'L' => 'CAR,B,1-2', 'M' => 'CAR,C,1-2', 'N' => 'CAR,A,2-2', 'O' => 'CAR,B,2-2', 'P' => 'CAR,C,2-2', 'Q' => 'CAR,A,1-1', 'R' => 'CAR,B,1-1', 'S' => 'CAR,C,1-1',
    'T' => 'CIR,A,1-1', 'U' => 'CIR,B,1-1', 'V' => 'CIR,C,1-1', 'W' => 'EMER,A,1-1', 'X' => 'EMER,B,1-1', 'Y' => 'EMER,C,1-1', 'Z' => 'ENDO,A,1-1', 'AA' => 'ENDO,B,1-1', 'AB' => 'ENDO,C,1-1', 
    'AC' => 'FIS,A,1-1', 'AD' => 'FIS,B,1-1', 'AE' => 'FIS,C,1-1', 'AF' => 'GAS,A,1-3', 'AG' => 'GAS,B,1-3', 'AH' => 'GAS,C,1-3', 'AI' => 'GAS,A,2-3', 'AJ' => 'GAS,B,2-3', 'AK' => 'GAS,C,2-3',
    'AL' => 'GAS,A,3-3', 'AM' => 'GAS,B,3-3', 'AN' => 'GAS,C,3-3', 'AO' => 'GAS,A,1-2', 'AP' => 'GAS,B,1-2', 'AQ' => 'GAS,C,1-2', 'AR' => 'GAS,A,2-2', 'AS' => 'GAS,B,2-2', 'AT' => 'GAS,C,2-2',
    'AU' => 'GAS,A,1-1', 'AV' => 'GAS,B,1-1', 'AW' => 'GAS,C,1-1', 'AX' => 'GIN,A,1-2', 'AY' => 'GIN,B,1-2', 'AX' => 'GIN,A,1-2', 'AY' => 'GIN,B,1-2', 'AZ' => 'GIN,C,1-2', 'BA' => 'GIN,A,2-2', 'BB' => 'GIN,B,2-2',
    'BC' => 'GIN,C,2-2', 'BD' => 'GIN,A,1-1', 'BE' => 'GIN,B,1-1', 'BF' => 'GIN,C,1-1', 'BG' => 'MGE,A,1-5', 'BH' => 'MGE,B,1-5', 'BI' => 'MGE,C,1-5', 'BJ' => 'MGE,A,2-5', 'BK' => 'MGE,B,2-5', 'BL' => 'MGE,C,2-5',
    'BM' => 'MGE,A,3-5', 'BN' => 'MGE,B,3-5', 'BO' => 'MGE,C,3-5', 'BP' => 'MGE,A,4-5', 'BQ' => 'MGE,B,4-5', 'BR' => 'MGE,C,4-5', 'BS' => 'MGE,A,5-5', 'BT' => 'MGE,B,5-5', 'BU' => 'MGE,C,5-5',
    'BV' => 'MGE,A,1-4', 'BW' => 'MGE,B,1-4', 'BX' => 'MGE,C,1-4', 'BY' => 'MGE,A,2-4', 'BZ' => 'MGE,B,2-4', 'CA' => 'MGE,C,2-4', 'CB' => 'MGE,A,3-4', 'CC' => 'MGE,B,3-4', 'CD' => 'MGE,C,3-4', 'CE' => 'MGE,A,4-4',
    'CF' => 'MGE,B,4-4', 'CG' => 'MGE,C,4-4', 'CH' => 'MGE,A,1-3', 'CI' => 'MGE,B,1-3', 'CJ' => 'MGE,C,1-3', 'CK' => 'MGE,A,2-3', 'CL' => 'MGE,B,2-3', 'CM' => 'MGE,C,2-3', 'CN' => 'MGE,A,3-3', 'CO' => 'MGE,B,3-3',
    'CP' => 'MGE,C,3-3', 'CQ' => 'MGE,A,1-2', 'CR' => 'MGE,B,1-2', 'CS' => 'MGE,C,1-2', 'CT' => 'MGE,A,2-2', 'CU' => 'MGE,B,2-2', 'CV' => 'MGE,C,2-2', 'CW' => 'MGE,A,1-1', 'CX' => 'MGE,B,1-1', 'CY' => 'MGE,C,1-1',
    'CZ' => 'MIN,A,1-4', 'DA' => 'MIN,B,1-4', 'DB' => 'MIN,C,1-4', 'DC' => 'MIN,A,2-4', 'DD' => 'MIN,B,2-4', 'DE' => 'MIN,C,2-4', 'DF' => 'MIN,A,3-4', 'DG' => 'MIN,B,3-4', 'DH' => 'MIN,C,3-4', 'DI' => 'MIN,A,4-4',
    'DJ' => 'MIN,B,4-4', 'DK' => 'MIN,C,4-4', 'DL' => 'MIN,A,1-3', 'DM' => 'MIN,B,1-3', 'DN' => 'MIN,C,1-3', 'DO' => 'MIN,A,2-3', 'DP' => 'MIN,B,2-3', 'DQ' => 'MIN,C,2-3', 'DR' => 'MIN,A,3-3', 'DS' => 'MIN,B,3-3',
    'DT' => 'MIN,C,3-3', 'DU' => 'MIN,A,1-2', 'DV' => 'MIN,B,1-2', 'DW' => 'MIN,C,1-2', 'DX' => 'MIN,A,2-2', 'DY' => 'MIN,B,2-2', 'DZ' => 'MIN,C,2-2', 'EA' => 'MIN,A,1-1', 'EB' => 'MIN,B,1-1', 'EC' => 'MIN,C,1-1',
    'ED' => 'NEF,A,1-1', 'EE' => 'NEF,B,1-1', 'EF' => 'NEF,C,1-1', 'EG' => 'NEUM,A,1-1', 'EH' => 'NEUM,B,1-1', 'EI' => 'NEUM,C,1-1', 'EJ' => 'NEUR,A,1-2', 'EK' => 'NEUR,B,1-2', 'EL' => 'NEUR,C,1-2', 'EM' => 'NEUR,A,2-2',
    'EN' => 'NEUR,B,2-2', 'EO' => 'NEUR,C,2-2', 'EP' => 'NEUR,A,1-1', 'EQ' => 'NEUR,B,1-1', 'ER' => 'NEUR,C,1-1', 'ES' => 'ODON,A,1-1', 'ET' => 'ODON,B,1-1', 'EU' => 'ODON,C,1-1', 'EV' => 'OFT,A,1-2', 'EW' => 'OFT,B,1-2',
    'EX' => 'OFT,C,1-2', 'EY' => 'OFT,A,2-2', 'EZ' => 'OFT,B,2-2', 'FA' => 'OFT,C,2-2', 'FB' => 'OFT,A,1-1', 'FC' => 'OFT,B,1-1', 'FD' => 'OFT,C,1-1', 'FE' => 'ONC,A,1-1', 'FF' => 'ONC,B,1-1', 'FG' => 'ONC,C,1-1',
    'FH' => 'OTO,A,1-2', 'FI' => 'OTO,B,1-2', 'FJ' => 'OTO,C,1-2', 'FK' => 'OTO,A,2-2', 'FL' => 'OTO,B,2-2', 'FM' => 'OTO,C,2-2', 'FN' => 'OTO,A,1-1', 'FO' => 'OTO,B,1-1', 'FP' => 'OTO,C,1-1', 'FQ' => 'REU,A,1-2',
    'FR' => 'REU,B,1-2', 'FS' => 'REU,C,1-2', 'FT' => 'REU,A,2-2', 'FU' => 'REU,B,2-2', 'FV' => 'REU,C,2-2', 'FW' => 'REU,A,1-1', 'FX' => 'REU,B,1-1', 'FY' => 'REU,C,1-1', 'FZ' => 'TRAUM,A,1-3', 'GA' => 'TRAUM,B,1-3',
    'GB' => 'TRAUM,C,1-3', 'GC' => 'TRAUM,A,2-3', 'GD' => 'TRAUM,B,2-3', 'GE' => 'TRAUM,C,2-3', 'GF' => 'TRAUM,A,3-3', 'GG' => 'TRAUM,B,3-3', 'GH' => 'TRAUM,C,3-3', 'GI' => 'TRAUM,A,1-2', 'GJ' => 'TRAUM,B,1-2', 'GK' => 'TRAUM,C,1-2',
    'GL' => 'TRAUM,A,2-2', 'GM' => 'TRAUM,B,2-2', 'GN' => 'TRAUM,C,2-2', 'GO' => 'TRAUM,A,1-1', 'GP' => 'TRAUM,B,1-1', 'GQ' => 'TRAUM,C,1-1', 'GR' => 'URO,A,1-1', 'GS' => 'URO,B,1-1', 'GT' => 'URO,C,1-1' );

// $array_final = array_combine($cabecera, $array_data);
// print_r($array_data);
foreach ($array_data as $hoja1 => $datos1) {
    // $array_combine = array_combine($cabecera, $datos1);
    $nom_producto = $datos1['A'];
    $codigo_producto = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$nom_producto%'");
    if(mysql_num_rows($codigo_producto) == '' or mysql_num_rows($codigo_producto) == 0 ){
        $codigo_final_producto = $nom_producto;
        $codigos_malos .= $nom_producto . ", ";
        $mal = $mal * 0;
    } else{
        $mal = $mal * 1;
        $codigo_final_producto = mysql_result($codigo_producto, 0, 0);
    }    
    
    $producto .= $codigo_final_producto . "," . 'CAR,A,1-3' . "," .$datos1['B'] . "," .
    $codigo_final_producto . "," . 'CAR,B,1-3' . "," .$datos1['C'] . "," .
    $codigo_final_producto . "," . 'CAR,C,1-3' . "," .$datos1['D'] . "," .
    $codigo_final_producto . "," . 'CAR,A,2-3' . "," .$datos1['E'] . "," .
    $codigo_final_producto . "," . 'CAR,B,2-3' . "," .$datos1['F'] . "," .
    $codigo_final_producto . "," . 'CAR,C,2-3' . "," .$datos1['G'] . "," .
    $codigo_final_producto . "," . 'CAR,A,3-3' . "," .$datos1['H'] . "," .
    $codigo_final_producto . "," . 'CAR,B,3-3' . "," .$datos1['I'] . "," .
    $codigo_final_producto . "," . 'CAR,C,3-3' . "," .$datos1['J'] . "," .
    $codigo_final_producto . "," . 'CAR,A,1-2' . "," .$datos1['K'] . "," .
    $codigo_final_producto . "," . 'CAR,B,1-2' . "," .$datos1['L'] . "," .
    $codigo_final_producto . "," . 'CAR,C,1-2' . "," .$datos1['M'] . "," .
    $codigo_final_producto . "," . 'CAR,A,2-2' . "," .$datos1['N'] . "," .
    $codigo_final_producto . "," . 'CAR,B,2-2' . "," .$datos1['O'] . "," .
    $codigo_final_producto . "," . 'CAR,C,2-2' . "," .$datos1['P'] . "," .
    $codigo_final_producto . "," . 'CAR,A,1-1' . "," .$datos1['Q'] . "," .
    $codigo_final_producto . "," . 'CAR,B,1-1' . "," .$datos1['R'] . "," .
    $codigo_final_producto . "," . 'CAR,C,1-1' . "," .$datos1['S'] . "," .
    $codigo_final_producto . "," . 'CIR,A,1-1' . "," .$datos1['T'] . "," .
    $codigo_final_producto . "," . 'CIR,B,1-1' . "," .$datos1['U'] . "," .
    $codigo_final_producto . "," . 'CIR,C,1-1' . "," .$datos1['V'] . "," .
    $codigo_final_producto . "," . 'EMER,A,1-1' . "," .$datos1['W'] . "," .
    $codigo_final_producto . "," . 'EMER,B,1-1' . "," .$datos1['X'] . "," .
    $codigo_final_producto . "," . 'EMER,C,1-1' . "," .$datos1['Y'] . "," .
    $codigo_final_producto . "," . 'ENDO,A,1-1' . "," .$datos1['Z'] . "," .
    $codigo_final_producto . "," . 'ENDO,B,1-1' . "," .$datos1['AA'] . "," .
    $codigo_final_producto . "," . 'ENDO,C,1-1' . "," .$datos1['AB'] . "," .
    $codigo_final_producto . "," . 'FIS,A,1-1' . "," .$datos1['AC'] . "," .
    $codigo_final_producto . "," . 'FIS,B,1-1' . "," .$datos1['AD'] . "," .
    $codigo_final_producto . "," . 'FIS,C,1-1' . "," .$datos1['AE'] . "," .
    $codigo_final_producto . "," . 'GAS,A,1-3' . "," .$datos1['AF'] . "," .
    $codigo_final_producto . "," . 'GAS,B,1-3' . "," .$datos1['AG'] . "," .
    $codigo_final_producto . "," . 'GAS,C,1-3' . "," .$datos1['AH'] . "," .
    $codigo_final_producto . "," . 'GAS,A,2-3' . "," .$datos1['AI'] . "," .
    $codigo_final_producto . "," . 'GAS,B,2-3' . "," .$datos1['AJ'] . "," .
    $codigo_final_producto . "," . 'GAS,C,2-3' . "," .$datos1['AK'] . "," .
    $codigo_final_producto . "," . 'GAS,A,3-3' . "," .$datos1['AL'] . "," .
    $codigo_final_producto . "," . 'GAS,B,3-3' . "," .$datos1['AM'] . "," .
    $codigo_final_producto . "," . 'GAS,C,3-3' . "," .$datos1['AN'] . "," .
    $codigo_final_producto . "," . 'GAS,A,1-2' . "," .$datos1['AO'] . "," .
    $codigo_final_producto . "," . 'GAS,B,1-2' . "," .$datos1['AP'] . "," .
    $codigo_final_producto . "," . 'GAS,C,1-2' . "," .$datos1['AQ'] . "," .
    $codigo_final_producto . "," . 'GAS,A,2-2' . "," .$datos1['AR'] . "," .
    $codigo_final_producto . "," . 'GAS,B,2-2' . "," .$datos1['AS'] . "," .
    $codigo_final_producto . "," . 'GAS,C,2-2' . "," .$datos1['AT'] . "," .
    $codigo_final_producto . "," . 'GAS,A,1-1' . "," .$datos1['AU'] . "," .
    $codigo_final_producto . "," . 'GAS,B,1-1' . "," .$datos1['AV'] . "," .
    $codigo_final_producto . "," . 'GAS,C,1-1' . "," .$datos1['AW'] . "," .
    $codigo_final_producto . "," . 'GIN,A,1-2' . "," .$datos1['AX'] . "," .
    $codigo_final_producto . "," . 'GIN,B,1-2' . "," .$datos1['AY'] . "," .
    $codigo_final_producto . "," . 'GIN,C,1-2' . "," .$datos1['AZ'] . "," .
    $codigo_final_producto . "," . 'GIN,A,2-2' . "," .$datos1['BA'] . "," .
    $codigo_final_producto . "," . 'GIN,B,2-2' . "," .$datos1['BB'] . "," .
    $codigo_final_producto . "," . 'GIN,C,2-2' . "," .$datos1['BC'] . "," .
    $codigo_final_producto . "," . 'GIN,A,1-1' . "," .$datos1['BD'] . "," .
    $codigo_final_producto . "," . 'GIN,B,1-1' . "," .$datos1['BE'] . "," .
    $codigo_final_producto . "," . 'GIN,C,1-1' . "," .$datos1['BF'] . "," .
    $codigo_final_producto . "," . 'MGE,A,1-5' . "," .$datos1['BG'] . "," .
    $codigo_final_producto . "," . 'MGE,B,1-5' . "," .$datos1['BH'] . "," .
    $codigo_final_producto . "," . 'MGE,C,1-5' . "," .$datos1['BI'] . "," .
    $codigo_final_producto . "," . 'MGE,A,2-5' . "," .$datos1['BJ'] . "," .
    $codigo_final_producto . "," . 'MGE,B,2-5' . "," .$datos1['BK'] . "," .
    $codigo_final_producto . "," . 'MGE,C,2-5' . "," .$datos1['BL'] . "," .
    $codigo_final_producto . "," . 'MGE,A,3-5' . "," .$datos1['BM'] . "," .
    $codigo_final_producto . "," . 'MGE,B,3-5' . "," .$datos1['BN'] . "," .
    $codigo_final_producto . "," . 'MGE,C,3-5' . "," .$datos1['BO'] . "," .
    $codigo_final_producto . "," . 'MGE,A,4-5' . "," .$datos1['BP'] . "," .
    $codigo_final_producto . "," . 'MGE,B,4-5' . "," .$datos1['BQ'] . "," .
    $codigo_final_producto . "," . 'MGE,C,4-5' . "," .$datos1['BR'] . "," .
    $codigo_final_producto . "," . 'MGE,A,5-5' . "," .$datos1['BS'] . "," .
    $codigo_final_producto . "," . 'MGE,B,5-5' . "," .$datos1['BT'] . "," .
    $codigo_final_producto . "," . 'MGE,C,5-5' . "," .$datos1['BU'] . "," .
    $codigo_final_producto . "," . 'MGE,A,1-4' . "," .$datos1['BV'] . "," .
    $codigo_final_producto . "," . 'MGE,B,1-4' . "," .$datos1['BW'] . "," .
    $codigo_final_producto . "," . 'MGE,C,1-4' . "," .$datos1['BX'] . "," .
    $codigo_final_producto . "," . 'MGE,A,2-4' . "," .$datos1['BY'] . "," .
    $codigo_final_producto . "," . 'MGE,B,2-4' . "," .$datos1['BZ'] . "," .
    $codigo_final_producto . "," . 'MGE,C,2-4' . "," .$datos1['CA'] . "," .
    $codigo_final_producto . "," . 'MGE,A,3-4' . "," .$datos1['CB'] . "," .
    $codigo_final_producto . "," . 'MGE,B,3-4' . "," .$datos1['CC'] . "," .
    $codigo_final_producto . "," . 'MGE,C,3-4' . "," .$datos1['CD'] . "," .
    $codigo_final_producto . "," . 'MGE,A,4-4' . "," .$datos1['CE'] . "," .
    $codigo_final_producto . "," . 'MGE,B,4-4' . "," .$datos1['CF'] . "," .
    $codigo_final_producto . "," . 'MGE,C,4-4' . "," .$datos1['CG'] . "," .
    $codigo_final_producto . "," . 'MGE,A,1-3' . "," .$datos1['CH'] . "," .
    $codigo_final_producto . "," . 'MGE,B,1-3' . "," .$datos1['CI'] . "," .
    $codigo_final_producto . "," . 'MGE,C,1-3' . "," .$datos1['CJ'] . "," .
    $codigo_final_producto . "," . 'MGE,A,2-3' . "," .$datos1['CK'] . "," .
    $codigo_final_producto . "," . 'MGE,B,2-3' . "," .$datos1['CL'] . "," .
    $codigo_final_producto . "," . 'MGE,C,2-3' . "," .$datos1['CM'] . "," .
    $codigo_final_producto . "," . 'MGE,A,3-3' . "," .$datos1['CN'] . "," .
    $codigo_final_producto . "," . 'MGE,B,3-3' . "," .$datos1['CO'] . "," .
    $codigo_final_producto . "," . 'MGE,C,3-3' . "," .$datos1['CP'] . "," .
    $codigo_final_producto . "," . 'MGE,A,1-2' . "," .$datos1['CQ'] . "," .
    $codigo_final_producto . "," . 'MGE,B,1-2' . "," .$datos1['CR'] . "," .
    $codigo_final_producto . "," . 'MGE,C,1-2' . "," .$datos1['CS'] . "," .
    $codigo_final_producto . "," . 'MGE,A,2-2' . "," .$datos1['CT'] . "," .
    $codigo_final_producto . "," . 'MGE,B,2-2' . "," .$datos1['CU'] . "," .
    $codigo_final_producto . "," . 'MGE,C,2-2' . "," .$datos1['CV'] . "," .
    $codigo_final_producto . "," . 'MGE,A,1-1' . "," .$datos1['CW'] . "," .
    $codigo_final_producto . "," . 'MGE,B,1-1' . "," .$datos1['CX'] . "," .
    $codigo_final_producto . "," . 'MGE,C,1-1' . "," .$datos1['CY'] . "," .
    $codigo_final_producto . "," . 'MIN,A,1-4' . "," .$datos1['CZ'] . "," .
    $codigo_final_producto . "," . 'MIN,B,1-4' . "," .$datos1['DA'] . "," .
    $codigo_final_producto . "," . 'MIN,C,1-4' . "," .$datos1['DB'] . "," .
    $codigo_final_producto . "," . 'MIN,A,2-4' . "," .$datos1['DC'] . "," .
    $codigo_final_producto . "," . 'MIN,B,2-4' . "," .$datos1['DD'] . "," .
    $codigo_final_producto . "," . 'MIN,C,2-4' . "," .$datos1['DE'] . "," .
    $codigo_final_producto . "," . 'MIN,A,3-4' . "," .$datos1['DF'] . "," .
    $codigo_final_producto . "," . 'MIN,B,3-4' . "," .$datos1['DG'] . "," .
    $codigo_final_producto . "," . 'MIN,C,3-4' . "," .$datos1['DH'] . "," .
    $codigo_final_producto . "," . 'MIN,A,4-4' . "," .$datos1['DI'] . "," .
    $codigo_final_producto . "," . 'MIN,B,4-4' . "," .$datos1['DJ'] . "," .
    $codigo_final_producto . "," . 'MIN,C,4-4' . "," .$datos1['DK'] . "," .
    $codigo_final_producto . "," . 'MIN,A,1-3' . "," .$datos1['DL'] . "," .
    $codigo_final_producto . "," . 'MIN,B,1-3' . "," .$datos1['DM'] . "," .
    $codigo_final_producto . "," . 'MIN,C,1-3' . "," .$datos1['DN'] . "," .
    $codigo_final_producto . "," . 'MIN,A,2-3' . "," .$datos1['DO'] . "," .
    $codigo_final_producto . "," . 'MIN,B,2-3' . "," .$datos1['DP'] . "," .
    $codigo_final_producto . "," . 'MIN,C,2-3' . "," .$datos1['DQ'] . "," .
    $codigo_final_producto . "," . 'MIN,A,3-3' . "," .$datos1['DR'] . "," .
    $codigo_final_producto . "," . 'MIN,B,3-3' . "," .$datos1['DS'] . "," .
    $codigo_final_producto . "," . 'MIN,C,3-3' . "," .$datos1['DT'] . "," .
    $codigo_final_producto . "," . 'MIN,A,1-2' . "," .$datos1['DU'] . "," .
    $codigo_final_producto . "," . 'MIN,B,1-2' . "," .$datos1['DV'] . "," .
    $codigo_final_producto . "," . 'MIN,C,1-2' . "," .$datos1['DW'] . "," .
    $codigo_final_producto . "," . 'MIN,A,2-2' . "," .$datos1['DX'] . "," .
    $codigo_final_producto . "," . 'MIN,B,2-2' . "," .$datos1['DY'] . "," .
    $codigo_final_producto . "," . 'MIN,C,2-2' . "," .$datos1['DZ'] . "," .
    $codigo_final_producto . "," . 'MIN,A,1-1' . "," .$datos1['EA'] . "," .
    $codigo_final_producto . "," . 'MIN,B,1-1' . "," .$datos1['EB'] . "," .
    $codigo_final_producto . "," . 'MIN,C,1-1' . "," .$datos1['EC'] . "," .
    $codigo_final_producto . "," . 'NEF,A,1-1' . "," .$datos1['ED'] . "," .
    $codigo_final_producto . "," . 'NEF,B,1-1' . "," .$datos1['EE'] . "," .
    $codigo_final_producto . "," . 'NEF,C,1-1' . "," .$datos1['EF'] . "," .
    $codigo_final_producto . "," . 'NEUM,A,1-1' . "," .$datos1['EG'] . "," .
    $codigo_final_producto . "," . 'NEUM,B,1-1' . "," .$datos1['EH'] . "," .
    $codigo_final_producto . "," . 'NEUM,C,1-1' . "," .$datos1['EI'] . "," .
    $codigo_final_producto . "," . 'NEUR,A,1-2' . "," .$datos1['EJ'] . "," .
    $codigo_final_producto . "," . 'NEUR,B,1-2' . "," .$datos1['EK'] . "," .
    $codigo_final_producto . "," . 'NEUR,C,1-2' . "," .$datos1['EL'] . "," .
    $codigo_final_producto . "," . 'NEUR,A,2-2' . "," .$datos1['EM'] . "," .
    $codigo_final_producto . "," . 'NEUR,B,2-2' . "," .$datos1['EN'] . "," .
    $codigo_final_producto . "," . 'NEUR,C,2-2' . "," .$datos1['EO'] . "," .
    $codigo_final_producto . "," . 'NEUR,A,1-1' . "," .$datos1['EP'] . "," .
    $codigo_final_producto . "," . 'NEUR,B,1-1' . "," .$datos1['EQ'] . "," .
    $codigo_final_producto . "," . 'NEUR,C,1-1' . "," .$datos1['ER'] . "," .
    $codigo_final_producto . "," . 'ODON,A,1-1' . "," .$datos1['ES'] . "," .
    $codigo_final_producto . "," . 'ODON,B,1-1' . "," .$datos1['ET'] . "," .
    $codigo_final_producto . "," . 'ODON,C,1-1' . "," .$datos1['EU'] . "," .
    $codigo_final_producto . "," . 'OFT,A,1-2' . "," .$datos1['EV'] . "," .
    $codigo_final_producto . "," . 'OFT,B,1-2' . "," .$datos1['EW'] . "," .
    $codigo_final_producto . "," . 'OFT,C,1-2' . "," .$datos1['EX'] . "," .
    $codigo_final_producto . "," . 'OFT,A,2-2' . "," .$datos1['EY'] . "," .
    $codigo_final_producto . "," . 'OFT,B,2-2' . "," .$datos1['EZ'] . "," .
    $codigo_final_producto . "," . 'OFT,C,2-2' . "," .$datos1['FA'] . "," .
    $codigo_final_producto . "," . 'OFT,A,1-1' . "," .$datos1['FB'] . "," .
    $codigo_final_producto . "," . 'OFT,B,1-1' . "," .$datos1['FC'] . "," .
    $codigo_final_producto . "," . 'OFT,C,1-1' . "," .$datos1['FD'] . "," .
    $codigo_final_producto . "," . 'ONC,A,1-1' . "," .$datos1['FE'] . "," .
    $codigo_final_producto . "," . 'ONC,B,1-1' . "," .$datos1['FF'] . "," .
    $codigo_final_producto . "," . 'ONC,C,1-1' . "," .$datos1['FG'] . "," .
    $codigo_final_producto . "," . 'OTO,A,1-2' . "," .$datos1['FH'] . "," .
    $codigo_final_producto . "," . 'OTO,B,1-2' . "," .$datos1['FI'] . "," .
    $codigo_final_producto . "," . 'OTO,C,1-2' . "," .$datos1['FJ'] . "," .
    $codigo_final_producto . "," . 'OTO,A,2-2' . "," .$datos1['FK'] . "," .
    $codigo_final_producto . "," . 'OTO,B,2-2' . "," .$datos1['FL'] . "," .
    $codigo_final_producto . "," . 'OTO,C,2-2' . "," .$datos1['FM'] . "," .
    $codigo_final_producto . "," . 'OTO,A,1-1' . "," .$datos1['FN'] . "," .
    $codigo_final_producto . "," . 'OTO,B,1-1' . "," .$datos1['FO'] . "," .
    $codigo_final_producto . "," . 'OTO,C,1-1' . "," .$datos1['FP'] . "," .
    $codigo_final_producto . "," . 'REU,A,1-2' . "," .$datos1['FQ'] . "," .
    $codigo_final_producto . "," . 'REU,B,1-2' . "," .$datos1['FR'] . "," .
    $codigo_final_producto . "," . 'REU,C,1-2' . "," .$datos1['FS'] . "," .
    $codigo_final_producto . "," . 'REU,A,2-2' . "," .$datos1['FT'] . "," .
    $codigo_final_producto . "," . 'REU,B,2-2' . "," .$datos1['FU'] . "," .
    $codigo_final_producto . "," . 'REU,C,2-2' . "," .$datos1['FV'] . "," .
    $codigo_final_producto . "," . 'REU,A,1-1' . "," .$datos1['FW'] . "," .
    $codigo_final_producto . "," . 'REU,B,1-1' . "," .$datos1['FX'] . "," .
    $codigo_final_producto . "," . 'REU,C,1-1' . "," .$datos1['FY'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,1-3' . "," .$datos1['FZ'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,1-3' . "," .$datos1['GA'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,1-3' . "," .$datos1['GB'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,2-3' . "," .$datos1['GC'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,2-3' . "," .$datos1['GD'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,2-3' . "," .$datos1['GE'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,3-3' . "," .$datos1['GF'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,3-3' . "," .$datos1['GG'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,3-3' . "," .$datos1['GH'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,1-2' . "," .$datos1['GI'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,1-2' . "," .$datos1['GJ'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,1-2' . "," .$datos1['GK'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,2-2' . "," .$datos1['GL'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,2-2' . "," .$datos1['GM'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,2-2' . "," .$datos1['GN'] . "," .
    $codigo_final_producto . "," . 'TRAUM,A,1-1' . "," .$datos1['GO'] . "," .
    $codigo_final_producto . "," . 'TRAUM,B,1-1' . "," .$datos1['GP'] . "," .
    $codigo_final_producto . "," . 'TRAUM,C,1-1' . "," .$datos1['GQ'] . "," .
    $codigo_final_producto . "," . 'URO,A,1-1' . "," .$datos1['GR'] . "," .
    $codigo_final_producto . "," . 'URO,B,1-1' . "," .$datos1['GS'] . "," .
    $codigo_final_producto . "," . 'URO,C,1-1' . "," .$datos1['GT'] . ",";
}
if($mal == 0){
    $codigos_malos_subs = substr($codigos_malos, 0, -2);
    $codigos_malos_finales = explode(",", $codigos_malos_subs);
    $codigos_malos_finales_sd=array_unique($codigos_malos_finales);

    $cadena_mal = "";
    $cadena_mal .= '<h2>Materiales No Encontrados (No se cargara el archivo, verifique por favor)</h2>';
    $cadena_mal .= '<table border="1">';
    $cadena_mal .= '<tr>';
    $cadena_mal .= '<td><strong>Nombre Material </strong></td>';
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
    $sql_id = mysql_query("SELECT max(id) from asignacion_mm_excel");
    $id = mysql_result($sql_id, 0, 0);

    if($id == '' or $id == null){
        $id = 1;
    }else{
        $id = $id + 1;
    }

    $sql_cabecera = mysql_query("INSERT into asignacion_mm_excel (id,ciclo,gestion,fecha) values ($id,$ciclo_final,$gestion_final,'$date')");
    
    $producto_sub = substr($producto, 0, -1);
    $producto_explode = explode(",", $producto_sub);
    $cadena_chunk = array_chunk($producto_explode, 5);

    $posicion = 1;

    foreach ($cadena_chunk as $a => $b) {
        $query = 'INSERT into asignacion_mm_excel_detalle (id,posicion,producto,especialidad,categoria,linea,cantidad) VALUES ('.$id.",".$posicion.",";
            foreach ($b as $c => $d) {
                $query .= "'" . $d . "',";
            }
        $query = substr($query, 0, -1);
        $query .= ");";
        mysql_query($query);
        $posicion = $posicion + 1; 
        // echo $query.";";
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