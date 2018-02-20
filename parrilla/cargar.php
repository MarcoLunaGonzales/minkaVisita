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
$cadena = '';

foreach ($rowIterator as $row) {
	$cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; //skip first row
    $rowIndex = $row->getRowIndex();
    // echo $rowIndex . " - ";
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '', 'I' => '', 'J' => '', 'K' => '', 'L' => '', 'M' => '', 'N' => '', 'O' => '', 'P' => '', 'Q' => '', 'R' => '', 'S' => '', 'T' => '', 'U' => '');

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
        } 
    }

}

$mal = 1 ;
$cadena = "";
$codigos_malos = '';
foreach ($array_data as $hoja1 => $datos1) {
    $especialidad = $datos1['A'];
    $linea = $datos1['B'];
    $posicion = $datos1['C'];
    $cod_llave = $datos1['D'];
    // $consolidado = $datos1['E'];
    $santa_cruz = $datos1['E'];
    $santa_cruz_periferie = $datos1['F'];
    $montero = $datos1['G'];
    $la_paz = $datos1['H'];
    $el_alto = $datos1['I'];
    $cochabamba = $datos1['J'];
    $quillacollo = $datos1['K'];
    $sucre = $datos1['L'];
    $tarija = $datos1['M'];
    $oruro = $datos1['N'];
    $potosi = $datos1['O'];
    $riberalta = $datos1['P'];
    $prov4 = $datos1['Q'];
    $prov2 = $datos1['R'];
    $prov1 = $datos1['S'];
    $cobija = $datos1['T'];
    $sacaba_punata = $datos1['U'];

    if($santa_cruz != 'Producto'){

        if($santa_cruz != ''){
            $sql_codigo_prodcuto_sc = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$santa_cruz%'");
            if(mysql_num_rows($sql_codigo_prodcuto_sc) == '' or mysql_num_rows($sql_codigo_prodcuto_sc) == 0 ){
                $codigo_prodcuto_sc = $santa_cruz;
                $mal = $mal * 0;
                $codigos_malos .= $santa_cruz . ", ";
            }else{
                $codigo_prodcuto_sc = mysql_result($sql_codigo_prodcuto_sc, 0, 0);    
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '116' . ",'" . $codigo_prodcuto_sc . "'," ;
        }
        if($santa_cruz_periferie != ''){
            $sql_codigo_prodcuto_scp = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$santa_cruz_periferie%'");
            if(mysql_num_rows($sql_codigo_prodcuto_scp) == '' or mysql_num_rows($sql_codigo_prodcuto_scp) == 0 ){
                $codigo_prodcuto_scp = $santa_cruz_periferie;
                $mal = $mal * 0;
                $codigos_malos .= $santa_cruz_periferie . ", ";
            }else{
                $codigo_prodcuto_scp = mysql_result($sql_codigo_prodcuto_scp, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '124' . ",'" . $codigo_prodcuto_scp . "',"  ;   
        }
        if($montero != ''){
            $sql_codigo_prodcuto_mon = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$montero%'");
            if(mysql_num_rows($sql_codigo_prodcuto_mon) == '' or mysql_num_rows($sql_codigo_prodcuto_mon) == 0 ){
                $codigo_prodcuto_mon = $montero;
                $codigos_malos .= $montero . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_mon = mysql_result($sql_codigo_prodcuto_mon, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '122' . ",'" . $codigo_prodcuto_mon . "',"  ;      
        }
        if($la_paz != ''){
            $sql_codigo_prodcuto_lp = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$la_paz%'");
            if(mysql_num_rows($sql_codigo_prodcuto_lp) == '' or mysql_num_rows($sql_codigo_prodcuto_lp) == 0 ){
                $codigo_prodcuto_lp = $la_paz;
                $codigos_malos .= $la_paz . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_lp = mysql_result($sql_codigo_prodcuto_lp, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '113' . ",'" . $codigo_prodcuto_lp . "',"  ;      
        } 
        if($el_alto != ''){
            $sql_codigo_prodcuto_ea = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$el_alto%'");
            if(mysql_num_rows($sql_codigo_prodcuto_ea) == '' or mysql_num_rows($sql_codigo_prodcuto_ea) == 0 ){
                $codigo_prodcuto_ea = $el_alto;
                $codigos_malos .= $el_alto . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_ea = mysql_result($sql_codigo_prodcuto_ea, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '114' . ",'" . $codigo_prodcuto_ea . "',"  ;      
        }
        if($cochabamba != ''){
            $sql_codigo_prodcuto_cbba = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$cochabamba%'");
            if(mysql_num_rows($sql_codigo_prodcuto_cbba) == '' or mysql_num_rows($sql_codigo_prodcuto_cbba) == 0 ){
                $codigo_prodcuto_cbba = $cochabamba;
                $codigos_malos .= $cochabamba . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_cbba = mysql_result($sql_codigo_prodcuto_cbba, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '102' . ",'" . $codigo_prodcuto_cbba . "',"  ;      
        }
        if($quillacollo != ''){
            $sql_codigo_prodcuto_qui = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$quillacollo%'");
            if(mysql_num_rows($sql_codigo_prodcuto_qui) == '' or mysql_num_rows($sql_codigo_prodcuto_qui) == 0 ){
                $codigo_prodcuto_qui = $quillacollo;
                $codigos_malos .= $quillacollo . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_qui = mysql_result($sql_codigo_prodcuto_qui, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '120' . ",'" . $codigo_prodcuto_qui . "',"  ;         
        }
        if($sucre != ''){
            $sql_codigo_prodcuto_sre = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$sucre%'");
            if(mysql_num_rows($sql_codigo_prodcuto_sre) == '' or mysql_num_rows($sql_codigo_prodcuto_sre) == 0 ){
                $codigo_prodcuto_sre = $sucre;
                $codigos_malos .= $sucre . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_sre = mysql_result($sql_codigo_prodcuto_sre, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '109' . ",'" . $codigo_prodcuto_sre . "',"  ;      
        }
        if($tarija != ''){
            $sql_codigo_prodcuto_tja = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$tarija%'");
            if(mysql_num_rows($sql_codigo_prodcuto_tja) == '' or mysql_num_rows($sql_codigo_prodcuto_tja) == 0 ){
                $codigo_prodcuto_tja = $tarija;
                $codigos_malos .= $tarija . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_tja = mysql_result($sql_codigo_prodcuto_tja, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '118' . ",'" . $codigo_prodcuto_tja . "',"  ;      
        }
        if($oruro != ''){
            $sql_codigo_prodcuto_oro = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$oruro%'");
            if(mysql_num_rows($sql_codigo_prodcuto_oro) == '' or mysql_num_rows($sql_codigo_prodcuto_oro) == 0 ){
                $codigo_prodcuto_oro = $oruro;
                $codigos_malos .= $oruro . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_oro = mysql_result($sql_codigo_prodcuto_oro, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '104' . ",'" . $codigo_prodcuto_oro . "',"  ;      
        }
        if($potosi != ''){
            $sql_codigo_prodcuto_pti = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$potosi%'");
            if(mysql_num_rows($sql_codigo_prodcuto_pti) == '' or mysql_num_rows($sql_codigo_prodcuto_pti) == 0 ){
                $codigo_prodcuto_pti = $potosi;
                $codigos_malos .= $potosi . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_pti = mysql_result($sql_codigo_prodcuto_pti, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '119' . ",'" . $codigo_prodcuto_pti . "',"  ;      
        }
        if($riberalta != ''){
            $sql_codigo_prodcuto_rba = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$riberalta%'");
            if(mysql_num_rows($sql_codigo_prodcuto_rba) == '' or mysql_num_rows($sql_codigo_prodcuto_rba) == 0 ){
                $codigo_prodcuto_rba = $riberalta;
                $codigos_malos .= $riberalta . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_rba = mysql_result($sql_codigo_prodcuto_rba, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '121' . ",'" . $codigo_prodcuto_rba . "',"  ;      
        }
        if($prov4 != ''){
            $sql_codigo_prodcuto_prov4 = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$prov4%'");
            if(mysql_num_rows($sql_codigo_prodcuto_prov4) == '' or mysql_num_rows($sql_codigo_prodcuto_prov4) == 0 ){
                $codigo_prodcuto_prov4 = $prov4;
                $codigos_malos .= $prov4 . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_prov4 = mysql_result($sql_codigo_prodcuto_prov4, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '1023' . ",'" . $codigo_prodcuto_prov4 . "',"  ;      
        }
        if($prov2 != ''){
            $sql_codigo_prodcuto_prov2 = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$prov2%'");
            if(mysql_num_rows($sql_codigo_prodcuto_prov2) == '' or mysql_num_rows($sql_codigo_prodcuto_prov2) == 0 ){
                $codigo_prodcuto_prov2 = $prov2;
                $codigos_malos .= $prov2 . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_prov2 = mysql_result($sql_codigo_prodcuto_prov2, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '1022' . ",'" . $codigo_prodcuto_prov2 . "',"  ;      
        }
        if($prov1 != ''){
            $sql_codigo_prodcuto_prov1 = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$prov1%'");
            if(mysql_num_rows($sql_codigo_prodcuto_prov1) == '' or mysql_num_rows($sql_codigo_prodcuto_prov1) == 0 ){
                $codigo_prodcuto_prov1 = $prov1;
                $codigos_malos .= $prov1 . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_prov1 = mysql_result($sql_codigo_prodcuto_prov1, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '1009' . ",'" . $codigo_prodcuto_prov1 . "',"  ;      
        }
        if($cobija != ''){
            $sql_codigo_prodcuto_cja = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$cobija%'");
            if(mysql_num_rows($sql_codigo_prodcuto_cja) == '' or mysql_num_rows($sql_codigo_prodcuto_cja) == 0 ){
                $codigo_prodcuto_cja = $cobija;
                $codigos_malos .= $cobija . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_cja = mysql_result($sql_codigo_prodcuto_cja, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '123' . ",'" . $codigo_prodcuto_cja . "',"  ;      
        }
        if($sacaba_punata != ''){
            $sql_codigo_prodcuto_sapu = mysql_query("SELECT codigo from muestras_medicas where CONCAT_WS(' ',descripcion,presentacion) like '%$sacaba_punata%'");
            if(mysql_num_rows($sql_codigo_prodcuto_sapu) == '' or mysql_num_rows($sql_codigo_prodcuto_sapu) == 0 ){
                $codigo_prodcuto_sapu = $sacaba_punata;
                $codigos_malos .= $sacaba_punata . ", ";
                $mal = $mal * 0;
            }else{
                $codigo_prodcuto_sapu = mysql_result($sql_codigo_prodcuto_sapu, 0, 0);
                $mal = $mal * 1;
            }
            $cadena .= "'" . $especialidad . "','" . $linea . "'," . $posicion . "," . '1031' . ",'" . $codigo_prodcuto_sapu . "',"  ;      
        }
    }
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

    $sql_id = mysql_query("SELECT max(id) from asignacion_productos_excel");
    $id = mysql_result($sql_id, 0, 0);

    if($id == '' or $id == null){
        $id = 1;
    }else{
        $id = $id + 1;
    }

    $sql_cabecera = mysql_query("INSERT into asignacion_productos_excel (id,ciclo,gestion,fecha) values ($id,$ciclo_final,$gestion_final,'$date')");
    
    $cadena_subs = substr($cadena, 0, -1);
    $cadena_explode = explode(",", $cadena_subs);
    $cadena_chunk = array_chunk($cadena_explode, 5);

    foreach ($cadena_chunk as $a => $b) {
        $query = 'INSERT into asignacion_productos_excel_detalle (id,especialidad, linea, posicion, ciudad, producto) VALUES ('.$id.",";
            foreach ($b as $c => $d) {
                $query .= $d . ",";
            }
            $query = substr($query, 0, -1);
            $query .= ");";
            mysql_query($query);
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