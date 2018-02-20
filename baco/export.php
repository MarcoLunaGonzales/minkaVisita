<?php

set_time_limit(0);
require 'coneccion.php';
require("../conexion-dir.inc");
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");

//echo "EXPORTAR BACOOOO";

mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '128M');

$archivo = $_REQUEST["archivo"];
$filename = dirname(__FILE__) . '/uploads/' . $archivo;

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);

$objPHPExcel->setActiveSheetIndex(0);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data = array();
foreach ($rowIterator as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); 
    if (1 == $row->getRowIndex())
        continue; 
    $rowIndex = $row->getRowIndex();
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '', 'I' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
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
        }
    }
}

$objPHPExcel->setActiveSheetIndex(1);
$rowIterator2 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data2 = array();
foreach ($rowIterator2 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    if (1 == $row->getRowIndex()){
        continue;
    }
    $rowIndex = $row->getRowIndex();
    $array_data2[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('C' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('D' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
        } else if ('E' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('F' == $cell->getColumn()) {
            $array_data2[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        }
    }
}

$objPHPExcel->setActiveSheetIndex(2);
$rowIterator3 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data3 = array();
foreach ($rowIterator3 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);
    if (1 == $row->getRowIndex())
        continue; 
    $rowIndex = $row->getRowIndex();
    $array_data3[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '', 'I' => '');


    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = PHPExcel_Style_NumberFormat::toFormattedString($cell->getCalculatedValue(), 'YYYY-MM-DD');
        } else if ('C' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('D' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('E' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('F' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('G' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('H' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('I' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        }
    }
}

$objPHPExcel->setActiveSheetIndex(3);
$rowIterator4 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data4 = array();
foreach ($rowIterator4 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); 
    if (1 == $row->getRowIndex()){
        continue; 
    }
    $rowIndex = $row->getRowIndex();
    $array_data4[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '');

    foreach ($cellIterator as $cell) {
        if ('A' == $cell->getColumn()) {
            $array_data4[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('B' == $cell->getColumn()) {
            $array_data4[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('C' == $cell->getColumn()) {
            $array_data4[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('D' == $cell->getColumn()) {
            $array_data4[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        }
    }
}

$codigos_entrada = "";
foreach ($array_data2 as $array_hoja2 => $array_datos2) {
    $codigo_entrada = $array_datos2["B"];
    $codigos_entrada .= $codigo_entrada . ",";
}
$codigos_salida = "";
foreach ($array_data4 as $array_hoja4 => $array_datos4) {
    $codigo_salida = $array_datos4["B"];
    $codigos_salida .= $codigo_salida . ",";
}

$codigos_totales = $codigos_entrada . $codigos_salida;
$codigos_totales = substr($codigos_totales, 0, -1);
$codigos_totales = explode(",", $codigos_totales);
$codigos_totales = array_unique($codigos_totales);
asort($codigos_totales);

$codigos_total_final = "";
foreach ($codigos_totales as $array_total => $array_total_valor) {
    $sql_verificacion = mssql_query(" SELECT * FROM MATERIALES WHERE COD_HERMES = $array_total_valor ");
    $num = mssql_num_rows($sql_verificacion);
    if ($num == 0) {
        $codigos_total_final .= $array_total_valor . ",";
    }
}
if ($codigos_total_final == '') {

    $sql_gestion = mssql_query("SELECT * from GESTIONES where GESTION_ESTADO = 1");
    $codigo_gestion = mssql_result($sql_gestion, 0, 0);

    $cod_tipo_inreso_almacen = 0;
    $cod_estado_ingreso_almacen = "1";
    $cod_almacen = "2";
    $estado_sistema = "1";
    $sql_codigo_baco = mssql_query(" SELECT max(cod_ingreso_almacen)  from ingresos_almacen ");
    $codigo_baco_ing = mssql_result($sql_codigo_baco, 0, 0);
    $codigo_baco_ing++;
    $codigo_ingreso_detalle_baco2 = $codigo_baco_ing;
    $codigo_ingreso_detalle_baco3 = $codigo_baco_ing;

    $txtNumIn="SELECT max(nro_ingreso_almacen) from ingresos_almacen where cod_almacen = $cod_almacen and cod_gestion = $codigo_gestion";
	//echo $num_in;
	$num_in = mssql_query($txtNumIn);
    
	
	$num_ingreso_almacen = mssql_result($num_in, 0, 0);
    $num_ingreso_almacen++;

    $output_ingreso = "";
    foreach ($array_data as $hoja1 => $datos1) {
        $codigo_hermes = $datos1['A'];
        $fecha = $datos1['B'];
        $hora = $datos1['C'];
        $tipo_ingreso = $datos1['D'];
        $obs = $datos1['E'];
        $orden_compra = $datos1['I'];
        
        if($tipo_ingreso == 1009){
            $cod_tipo_inreso_almacen = 6;
        }else{
            $cod_tipo_inreso_almacen = 1;
        }

        $oc = explode("/", $obs);
        $oc_limpio = preg_replace("/[^0-9]/", "", $oc[2]);
        if ($oc_limpio == '') {
            $oc_limpio = 0;
        } else {
            $oc_limpio = $oc_limpio;
        }

        //$orden_compra_baco = mssql_query("SELECT cod_orden_compra, cod_proveedor from ordenes_compra where cod_gestion = $codigo_gestion and NRO_ORDEN_COMPRA = $orden_compra");
		$orden_compra_baco = mssql_query("select top 1 cod_orden_compra, cod_proveedor from ordenes_compra where nro_orden_compra=$orden_compra order by cod_orden_compra desc");	
		//echo "SELECT cod_orden_compra, cod_proveedor from ordenes_compra where cod_gestion = $codigo_gestion and NRO_ORDEN_COMPRA = $orden_compra";
		
		if (mssql_num_rows($orden_compra_baco) == 0) {
            //$codigo_oreden_compra = 10000;
            //$codigo_proveedor = 160;
			$codigo_oreden_compra = 0;
            $codigo_proveedor = 0;
        } else {
            $codigo_oreden_compra = mssql_result($orden_compra_baco, 0, 0);
            $codigo_proveedor = mssql_result($orden_compra_baco, 0, 1);
        }


        $nota = $datos1['F'];
        $n_correlativo = $datos1['G'];
        $anulado = $datos1['H'];
        $fechaa = $fecha . " " . $hora . ".000";
        $output_ingreso .= $codigo_baco_ing . "," . $cod_tipo_inreso_almacen . "," . $codigo_oreden_compra . "," . $codigo_gestion . "," . $cod_estado_ingreso_almacen . "," . 0 . ",'" . $fechaa . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . ",'" . $obs . "'," . $num_ingreso_almacen . "," . $codigo_proveedor . "," . $estado_sistema . "," . 0 . "," . $cod_almacen . "," . 0 . "," . 0 . "," . 2 . "," . 0 . "," . 0 . "," . $codigo_hermes . ",";
        $codigo_baco_ing++;
        $num_ingreso_almacen++;
        $codigos_hermes .= $codigo_hermes . ",";
    }
    $output_ingreso = substr($output_ingreso, 0, -1);
    $output_ingreso = explode(",", $output_ingreso);
    $output_ingreso = array_chunk($output_ingreso, 23);


    $veri_hermes = "";
    $sql_veri_hermes = mssql_query(" SELECT codigo_hermes from ingresos_almacen where codigo_hermes != null or codigo_hermes != 0 ");
    if (mssql_num_rows($sql_veri_hermes) == 0) {

    } else {
        while ($row_veri_hermes = mssql_fetch_array($sql_veri_hermes)) {
            $veri_hermes .= $row_veri_hermes[0] . ",";
        }
        $veri_hermes = substr($veri_hermes, 0, -1);
        $veri_hermes = explode(",", $veri_hermes);
    }
    mssql_query("SET DATEFORMAT ymd");
    foreach ($output_ingreso as $a => $b) {

        if (in_array($b[22], $veri_hermes)) {
            $query = "UPDATE INGRESOS_ALMACEN set cod_tipo_ingreso_almacen = $b[1],COD_ORDEN_COMPRA = $b[2] cod_estado_ingreso_almacen = $b[4], FECHA_INGRESO_ALMACEN = $b[6], OBS_INGRESO_ALMACEN = $b[11], COD_PROVEEDOR = $b[13] ,ESTADO_SISTEMA = $b[14], COD_ALMACEN = $b[16] where  codigo_hermes = $b[22] ";
            // mssql_query($query);
        } else {
            $query = "INSERT into INGRESOS_ALMACEN (cod_ingreso_almacen, cod_tipo_ingreso_almacen, cod_orden_compra, cod_gestion, cod_estado_ingreso_almacen, cod_devolucion, FECHA_INGRESO_ALMACEN, COD_TIPO_DOCUMENTO, NRO_DOCUMENTO, FECHA_DOCUMENTO, CREDITO_FISCAL_SI_NO, OBS_INGRESO_ALMACEN, NRO_INGRESO_ALMACEN, COD_PROVEEDOR, ESTADO_SISTEMA, COD_TIPO_COMPRA, COD_ALMACEN, COD_SALIDA_ALMACEN, COD_PERSONAL, COD_ESTADO_INGRESO_LIQUIDACION, FECHA_LIQUIDACION, COD_SALIDA_ALMACEN_DEVOLUCION, codigo_hermes) VALUES (";
                foreach ($b as $c => $d) {
                    $query .= $d . ",";
                }
                $query = substr($query, 0, -1);
                $query .= ");";
				echo $query."<br>";
				
				mssql_query($query);
		}
}
$codigos_hermes = substr($codigos_hermes, 0, -1);

$cod_ingreso_almacen_baco = mssql_query(" SELECT max(COD_INGRESO_ALMACEN) from INGRESOS_ALMACEN_DETALLE ");
$codigo_ingreso_detalle_baco = mssql_result($cod_ingreso_almacen_baco, 0, 0);
$ver_codigo_ingreso_almacen = 0;

$ingresos_detalle = "";
$codigo_unidad_medida = 8;

foreach ($array_data2 as $hoja2 => $datos2) {
    $cod_ingreso_almacen = $datos2['A'];
    $cod_material = $datos2['B'];
    $nro_lote = $datos2['C'];
    $fecha_vencimiento = $datos2['D'];
    $cantidad_unitaria = $datos2['E'];
    $cantidad_restante = $datos2['F'];
    if ($ver_codigo_ingreso_almacen == $cod_ingreso_almacen) {
        $codigo_ingreso_detalle_baco2--;
    }

    $sql_cambio_cod_hermes = mssql_query(" SELECT cod_material,cod_grupo from materiales where cod_hermes = $cod_material ");
    $cod_nuevo_baco = mssql_result($sql_cambio_cod_hermes, 0, 0);
    $cod_grupo_material = mssql_result($sql_cambio_cod_hermes, 0, 1);
    if ($cod_grupo_material == 20) {
        mssql_query("update materiales set cod_grupo = 12 where cod_material = $cod_nuevo_baco and cod_grupo = 20");
    }
    $ingresos_detalle .= $cod_nuevo_baco . "," . $codigo_ingreso_detalle_baco2 . "," . $cod_almacen . "," . 0 . "," . $cantidad_unitaria . "," . $cantidad_unitaria . "," . $codigo_unidad_medida . "," . 0 . "," . 0 . "," . 0 . ",'" . 0 . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cod_ingreso_almacen . ",";
    $codigo_ingreso_detalle_baco2++;
    $ver_codigo_ingreso_almacen = $cod_ingreso_almacen;
}

$ingresos_detalle = substr($ingresos_detalle, 0, -1);
$ingresos_detalle = explode(",", $ingresos_detalle);
$ingresos_detalle = array_chunk($ingresos_detalle, 17);


$sql_veri_detalle = mssql_query(" SELECT cod_hermes from ingresos_almacen_detalle where cod_hermes != null or cod_hermes != 0 ");
if (mssql_num_rows($sql_veri_detalle) == 0) {

} else {
    while ($row_cod_ingreso_detalle = mssql_fetch_array($sql_veri_detalle)) {
        $veri_cod_detalle .= $row_cod_ingreso_detalle[0] . ",";
    }
    $veri_cod_detalle = substr($veri_cod_detalle, 0, -1);
    $veri_cod_detalle = explode(",", $veri_cod_detalle);
}
foreach ($ingresos_detalle as $ing => $det) {
    if (in_array($det[16], $veri_cod_detalle)) {
        $query_detalle = " UPDATE INGRESOS_ALMACEN_DETALLE SET COD_MATERIAL = $det[0], COD_SECCION = $det[2] where cod_hermes = $det[16]  ";
        // mssql_query($query_detalle);
    } else {
        $query_detalle = "INSERT into INGRESOS_ALMACEN_DETALLE (COD_MATERIAL,COD_INGRESO_ALMACEN,COD_SECCION,NRO_UNIDADES_EMPAQUE,CANT_TOTAL_INGRESO,CANT_TOTAL_INGRESO_FISICO,
            COD_UNIDAD_MEDIDA,PRECIO_TOTAL_MATERIAL,PRECIO_UNITARIO_MATERIAL,COSTO_UNITARIO,observacion,PRECIO_NETO,COSTO_PROMEDIO,COSTO_UNITARIO_ACTUALIZADO,
            FECHA_ACTUALIZACION,COSTO_UNITARIO_ACTUALIZADO_FINAL,cod_hermes) VALUES (";
            foreach ($det as $ingre => $detalle) {
                $query_detalle .= $detalle . ",";
            }
            $query_detalle = substr($query_detalle, 0, -1);
            $query_detalle .= ");";
			
			echo $query_detalle."<br>";
			mssql_query($query_detalle);
		}
}


$ingresos_detalles = "";
$ver_codigo_ingreso_almacen2 = 0;

foreach ($array_data2 as $hoja2a => $datos2a) {
    $cod_ingreso_almacen_s = $datos2a['A'];
    $cod_material_s = $datos2a['B'];
    $nro_lote_s = $datos2a['C'];
    $fecha_vencimiento_s = $datos2a['D'];
    $cantidad_unitaria_s = $datos2a['E'];
    $cantidad_restante_s = $datos2a['F'];
    if ($ver_codigo_ingreso_almacen2 == $cod_ingreso_almacen_s) {
        $codigo_ingreso_detalle_baco3--;
    }
    /**/

    $sql_cambio_cod_hermes_s = mssql_query(" SELECT cod_material from materiales where cod_hermes = $cod_material_s ");
    $cod_nuevo_baco_s = mssql_result($sql_cambio_cod_hermes_s, 0, 0);

    /**/
    $ingresos_detalles .= $cod_nuevo_baco_s . "," . $codigo_ingreso_detalle_baco3 . "," . $cod_almacen . "," . 0 . "," . $cantidad_unitaria_s . "," . $cantidad_unitaria_s . "," . $codigo_unidad_medida . "," . 0 . "," . 0 . "," . 0 . ",' '," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cod_ingreso_almacen_s . ",";
    $codigo_ingreso_detalle_baco3++;
    $ver_codigo_ingreso_almacen2 = $cod_ingreso_almacen_s;
}

$ingresos_detalles1 = substr($ingresos_detalles, 0, -1);
$ingresos_detalles2 = explode(",", $ingresos_detalles1);
$ingresos_detalles3 = array_chunk($ingresos_detalles2, 17);



$sql_veri_detalle_aux = mssql_query(" SELECT cod_hermes from ingresos_almacen_detalle_auxiliar where cod_hermes != null or cod_hermes != 0 ");
if (mssql_num_rows($sql_veri_detalle_aux) == 0) {

} else {
    while ($row_cod_ingreso_detalle_aux = mssql_fetch_array($sql_veri_detalle_aux)) {
        $veri_cod_detalle_aux .= $row_cod_ingreso_detalle_aux[0] . ",";
    }
    $veri_cod_detalle_aux = substr($veri_cod_detalle_aux, 0, -1);
    $veri_cod_detalle_aux = explode(",", $veri_cod_detalle_aux);
}
foreach ($ingresos_detalles3 as $ings => $dets) {
    if (in_array($dets[16], $veri_cod_detalle_aux)) {
        $query_detalles = " Update ingresos_almacen_detalle_auxiliar set cod_material = $dets[0] , cod_ingreos_almacen = $dets[1] where cod_hermes = $dets[16]  ";
        //echo $query_detalles;
//            mssql_query($query_detalles);
    } else {
        $query_detalles = "INSERT INTO INGRESOS_ALMACEN_DETALLE_AUXILIAR (COD_MATERIAL,COD_INGRESO_ALMACEN,COD_SECCION,NRO_UNIDADES_EMPAQUE,CANT_TOTAL_INGRESO,CANT_TOTAL_INGRESO_FISICO ,COD_UNIDAD_MEDIDA,PRECIO_TOTAL_MATERIAL,PRECIO_UNITARIO_MATERIAL,COSTO_UNITARIO,observacion,PRECIO_NETO,COSTO_UNITARIO_ACTUALIZADO,COSTO_PROMEDIO ,FECHA_ACTUALIZACION,COSTO_UNITARIO_ACTUALIZADO_FINAL,cod_hermes) VALUES("; 
            foreach ($dets as $ingres => $detalles) {
                $query_detalles .= $detalles . ",";
            }
            $query_detalles = substr($query_detalles, 0, -1);
            $query_detalles .= ");";
            echo ($query_detalles);
mssql_query($query_detalles);
}
}

/**/

/* -----------------------------FIN INGRESOS---------------------------- */

/* -----------------------------SALIDAS------------------------------------- */

$codigo_almacen = "2";
//    $cod_almacen_salida = "2";
$estado_salida_sitema = "1";
$codigo_tipo_salida = "1";
$cod_estado_salida_almacen = "1";
$cod_lote_produccion = ' ';
$sql_codigo_bacos = mssql_query(" SELECT max(cod_salida_almacen) from salidas_almacen ");
$codigo_baco_salida = mssql_result($sql_codigo_bacos, 0, 0);
$codigo_baco_salida++;
$codigo_salida_detalle_baco2 = $codigo_baco_salida;
$codigo_salida_detalle_baco3 = $codigo_baco_salida;

$sql_codigo_n_salida_almacen = mssql_query(" SELECT max(nro_salida_almacen) from salidas_almacen where cod_almacen = $cod_almacen and cod_gestion = $codigo_gestion");
$codigo_n_salida_almacen = mssql_result($sql_codigo_n_salida_almacen, 0, 0);
$codigo_n_salida_almacen++;

foreach ($array_data3 as $hoja3 => $datos_s3) {
    $codigo_salida_hermes = $datos_s3['A'];
    $fecha                = $datos_s3['B'];
    $hora                 = $datos_s3['C'];
    $nombre_salida        = $datos_s3['D'];
    $observaciones        = $datos_s3['E'];
    $nota_entrega         = $datos_s3['F'];
    $n_correlativo        = $datos_s3['G'];
    $salida_anulada       = $datos_s3['H'];
    $almacen_salida       = $datos_s3['I'];
    $fecha                = $fecha . " " . $hora . ".000";

    /* Almacen de salida */
    $codigo_almacen_salida = 1;

    if ($almacen_salida == 1000) {
        $codigo_almacen_salida = 90;
    }
    if ($almacen_salida == 1001) {
        $codigo_almacen_salida = 46;
    }
    if ($almacen_salida == 1002) {
        $codigo_almacen_salida = 49;
    }
    if ($almacen_salida == 1003) {
        $codigo_almacen_salida = 47;
    }
    if ($almacen_salida == 1004) {
        $codigo_almacen_salida = 51;
    }
    if ($almacen_salida == 1005) {
        $codigo_almacen_salida = 53;
    }
    if ($almacen_salida == 1006) {
        $codigo_almacen_salida = 54;
    }
    if ($almacen_salida == 1007) {
        $codigo_almacen_salida = 48;
    }
    if ($almacen_salida == 1008) {
        $codigo_almacen_salida = 56;
    }
    if ($almacen_salida == 1009) {
        $codigo_almacen_salida = 63;
    }
    if ($almacen_salida == 1010) {
        $codigo_almacen_salida = 52;
    }
    if ($almacen_salida == 1011) {
        $codigo_almacen_salida = 55;
    }
//        if ($almacen_salida != 1000 || $almacen_salida != 1001 || $almacen_salida != 1002 || $almacen_salida != 1003 || $almacen_salida != 1004 || $almacen_salida != 1005 || $almacen_salida != 1006 || $almacen_salida != 1007 || $almacen_salida != 1008 || $almacen_salida != 1009 || $almacen_salida != 1010 || $almacen_salida != 1011) {
//            $codigo_almacen_salida = 1;
//        }
    /* FIN */
    if($nombre_salida == 'POR NOTA DE ENTREGA'){
        $codigo_tipo_salida = 7;
    }
    if($nombre_salida == 'DEVOLUCION AL PROVEEDOR'){
         $codigo_tipo_salida = 10;
    }
	if($nombre_salida == 'MARKETING'){
         $codigo_tipo_salida = 1;
    }
	if($nombre_salida == 'TRASPASO REGIONAL'){
         $codigo_tipo_salida = 1;
    }


    $sql_registros_salidas = mysql_query("SELECT * from salida_almacenes where cod_salida_almacenes = $codigo_salida_hermes ");
    while ($row_salidas = mysql_fetch_array($sql_registros_salidas)) {
        $estado_salida = $row_salidas[8];
    }
    $output_salida .= $codigo_gestion . "@" . $codigo_baco_salida . "@" . 0 . "@" . 0 . "@" . 0 . "@" . $codigo_tipo_salida . "@" . $codigo_almacen_salida . "@" . $codigo_n_salida_almacen . "@'" . $fecha . "'@'" . $observaciones . "'@" . $estado_salida_sitema . "@" . $codigo_almacen . "@" . 0 . "@" . 429 . "@" . $cod_estado_salida_almacen . "@'" . $cod_lote_produccion . "'@" . 0 . "@" . 0 . "@" . 0 . "@" . 0 . "@" . $codigo_salida_hermes . "@";
    $codigo_baco_salida++;
    $codigo_n_salida_almacen++;
    $codigos_salida_hermes .= $codigo_salida_hermes . ",";
}

$output_salida = substr($output_salida, 0, -1);
$output_salida = explode("@", $output_salida);
$output_salida = array_chunk($output_salida, 21);

//    print_r($output_salida);
/**/

$veri_hermes_salida = "";
$sql_veri_hermes_salida = mssql_query(" SELECT codigo_hermes from SALIDAS_ALMACEN where codigo_hermes != null or codigo_hermes != 0 ");
if (mssql_num_rows($sql_veri_hermes_salida) == 0) {

} else {
    while ($row_veri_hermes_salida = mssql_fetch_array($sql_veri_hermes_salida)) {
        $veri_hermes_salida .= $row_veri_hermes_salida[0] . ",";
    }
    $veri_hermes_salida = substr($veri_hermes_salida, 0, -1);
    $veri_hermes_salida = explode(",", $veri_hermes_salida);
}

foreach ($output_salida as $aa => $bb) {
    if (in_array($bb[20], $veri_hermes_salida)) {
        $query2 = " UPDATE SALIDAS_ALMACEN set  COD_TIPO_SALIDA_ALMACEN = $bb[5], COD_AREA_EMPRESA = $bb[6],
        FECHA_SALIDA_ALMACEN = $bb[8], OBS_SALIDA_ALMACEN = $bb[9], ESTADO_SISTEMA = $bb[10], COD_ALMACEN = $bb[11], 
        COD_ESTADO_SALIDA_ALMACEN = $bb[14] where codigo_hermes = $bb[20]  ";
//            mssql_query($query2);
    } else {
        $query2 = "INSERT into salidas_almacen (COD_GESTION,COD_SALIDA_ALMACEN,COD_ORDEN_PESADA,COD_FORM_SALIDA,COD_PROD,COD_TIPO_SALIDA_ALMACEN,COD_AREA_EMPRESA,NRO_SALIDA_ALMACEN,FECHA_SALIDA_ALMACEN,
            OBS_SALIDA_ALMACEN, ESTADO_SISTEMA, COD_ALMACEN, COD_ORDEN_COMPRA, COD_PERSONAL, COD_ESTADO_SALIDA_ALMACEN, COD_LOTE_PRODUCCION, COD_ESTADO_SALIDA_COSTO, cod_prod_ant,
            orden_trabajo, COD_PRESENTACION, codigo_hermes) VALUES (";
            foreach ($bb as $cc => $dd) {
                $query2 .= $dd . ",";
            }
            $query2 = substr($query2, 0, -1);
            $query2 .= ");";
            // echo $query2;
mssql_query($query2);
}
}

/* DETALLES */

$codigos_salida_hermes = substr($codigos_salida_hermes, 0, -1);
$cod_salida_almacen_baco = mssql_query(" SELECT max(COD_SALIDA_ALMACEN) from SALIDAS_ALMACEN_DETALLE ");
$codigo_salida_detalle_baco = mssql_result($cod_salida_almacen_baco, 0, 0);
//    $codigo_salida_detalle_baco2 = $codigo_baco_salida;
//    $codigo_salida_detalle_baco3 = $codigo_baco_salida;
//    $codigo_salida_detalle_baco2++;
//    $codigo_salida_detalle_baco3++;
$ver_salida_detalle_baco = 0;
$ver_salida_detalle_baco2 = 0;

$salidas_detalle = "";

foreach ($array_data4 as $hoja4 => $datos4) {
    $cod_salida_almacen = $datos4['A'];
    $cod_material = $datos4['B'];
    $cantidad_unitaria = $datos4['C'];
    $observaciones = $datos4['D'];
    if ($cod_salida_almacen == $ver_salida_detalle_baco) {
        $codigo_salida_detalle_baco2--;
    }
    /**/

    $sql_cambio_cod_hermes_salida = mssql_query(" SELECT cod_material,cod_grupo from materiales where cod_hermes = $cod_material ");
    $cod_nuevo_baco_salida = mssql_result($sql_cambio_cod_hermes_salida, 0, 0);
    $cod_grupo_material_salida = mssql_result($sql_cambio_cod_hermes_salida, 0, 1);
    if ($cod_grupo_material_salida == 20) {
        mssql_query("update materiales set cod_grupo = 12 where cod_material = $cod_nuevo_baco_salida and cod_grupo = 20");
    }
        // mssql_query("update materiales set material_almacen = 1 where cod_material = $cod_nuevo_baco_salida and material_almacen = 0");

    /**/
    $salidas_detalle .= $codigo_salida_detalle_baco2 . "," . $cod_nuevo_baco_salida . "," . $cantidad_unitaria . "," . $codigo_unidad_medida . "," . 2 . "," . $cod_salida_almacen . ",";
    $codigo_salida_detalle_baco2++;
    $ver_salida_detalle_baco = $cod_salida_almacen;
}

$salidas_detalle = substr($salidas_detalle, 0, -1);
$salidas_detalle = explode(",", $salidas_detalle);
$salidas_detalle = array_chunk($salidas_detalle, 6);

$sql_veri_detalle_salida = mssql_query(" SELECT cod_hermes from salidas_almacen_detalle where cod_hermes != null or cod_hermes != 0 ");
if (mssql_num_rows($sql_veri_detalle_salida) == 0) {

} else {
    while ($row_cod_salida_detalle = mssql_fetch_array($sql_veri_detalle_salida)) {
        $veri_cod_detalle_salida .= $row_cod_salida_detalle[0] . ",";
    }
    $veri_cod_detalle_salida = substr($veri_cod_detalle_salida, 0, -1);
    $veri_cod_detalle_salida = explode(",", $veri_cod_detalle_salida);
}

foreach ($salidas_detalle as $sal => $dett) {
    if (in_array($dett[5], $veri_cod_detalle_salida)) {
        $query_detalle = "UPDATE salidas_almacen_detalle set   COD_MATERIAL = $dett[1] where cod_hermes  = $dett[5] ";
//            echo $query_detalle. "<br />";
//            mssql_query($query_detalle);
    } else {
        $query_detalle = "INSERT into salidas_almacen_detalle (COD_SALIDA_ALMACEN,COD_MATERIAL,CANTIDAD_SALIDA_ALMACEN,COD_UNIDAD_MEDIDA,COD_ESTADO_MATERIAL,cod_hermes) VALUES (";
            foreach ($dett as $ingres => $detallee) {
                $query_detalle .= $detallee . ",";
            }
            $query_detalle = substr($query_detalle, 0, -1);
            $query_detalle .= ");";
//            echo $query_detalle. "<br />";
mssql_query($query_detalle);
}
}

    /*
     * COD_SALIDA_ALMACEN,COD_MATERIAL,COD_INGRESO_ALMACEN,ETIQUETA,COSTO_SALIDA,FECHA_VENCIMIENTO,CANTIDAD,COSTO_SALIDA_ACTUALIZADO,
     * FECHA_ACTUALIZACION,COSTO_SALIDA_ACTUALIZADO_FINAL
     */

    $salidas_detalles = "";

    foreach ($array_data4 as $hoja4a => $datos4a) {
        $cod_salida_almacen_s = $datos4a['A'];
        $cod_material_salida_s = $datos4a['B'];
        $cantidad_unitaria_s = $datos4a['C'];
        $observaciones_s = $datos4a['D'];
        /**/
        if ($cod_salida_almacen_s == $ver_salida_detalle_baco2) {
            $codigo_salida_detalle_baco3--;
        }

        $sql_cambio_cod_hermes_salida_s = mssql_query(" SELECT cod_material from materiales where cod_hermes = $cod_material_salida_s ");
        $cod_nuevo_baco_salida_s = mssql_result($sql_cambio_cod_hermes_salida_s, 0, 0);

        /**/
        $salidas_detalles .= $codigo_salida_detalle_baco3 . "," . $cod_nuevo_baco_salida_s . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cantidad_unitaria_s . "," . 0 . "," . 0 . "," . 0 . "," . $cod_salida_almacen_s . ",";
        $codigo_salida_detalle_baco3++;
        $ver_salida_detalle_baco2 = $cod_salida_almacen_s;
    }

    $salidas_detalles = substr($salidas_detalles, 0, -1);
    $salidas_detalles = explode(",", $salidas_detalles);
    $salidas_detalles = array_chunk($salidas_detalles, 11);

    $sql_veri_detalle_aux_s = mssql_query(" SELECT cod_hermes from SALIDAS_ALMACEN_DETALLE_INGRESO where cod_hermes != null or cod_hermes != 0 ");
    if (mssql_num_rows($sql_veri_detalle_aux_s) == 0) {

    } else {
        while ($row_cod_ingreso_detalle_aux_s = mssql_fetch_array($sql_veri_detalle_aux_s)) {
            $veri_cod_detalle_aux_s .= $row_cod_ingreso_detalle_aux_s[0] . ",";
        }
        $veri_cod_detalle_aux_s = substr($veri_cod_detalle_aux_s, 0, -1);
        $veri_cod_detalle_aux_s = explode(",", $veri_cod_detalle_aux_s);
    }
    foreach ($salidas_detalles as $ings_s => $dets_s) {
        if (in_array($dets_s[16], $veri_cod_detalle_aux_s)) {
            $query_detalles_s = " UPDATE SALIDAS_ALMACEN_DETALLE_INGRESO SET cod_salida_almacen = $dets_s[0], cod_material = $dets_s[1] where cod_hermes = $dets_s[10]  ";
//            mssql_query($query_detalles_s);
        } else {
            $query_detalles_s = "INSERT INTO SALIDAS_ALMACEN_DETALLE_INGRESO (COD_SALIDA_ALMACEN,COD_MATERIAL, COD_INGRESO_ALMACEN, ETIQUETA, COSTO_SALIDA,FECHA_VENCIMIENTO,
                CANTIDAD,COSTO_SALIDA_ACTUALIZADO,FECHA_ACTUALIZACION,COSTO_SALIDA_ACTUALIZADO_FINAL,cod_hermes) VALUES(";
                foreach ($dets_s as $ingres_s => $detalles_s) {
                    $query_detalles_s .= $detalles_s . ",";
                }
                $query_detalles_s = substr($query_detalles_s, 0, -1);
                $query_detalles_s .= ");";
mssql_query($query_detalles_s);
}
}

/**/

$cadena = "";
$cadena .= '<h2>Todos los materiales tienen su codigo hermes respectivo</h2>';
$cadena .= '<h3>Los datos se guardaron en la base de datos de BACO</h3>';
$arr = array("mensaje" => "vacio", "cadena" => $cadena);

echo json_encode($arr);


/* Fin para guardar */
} else {
    $codigos_total_final = substr($codigos_total_final, 0, -1);
    $codigos_total_final = explode(",", $codigos_total_final);

    $cadena = "";
    $cadena .= '<h2>Materiales con codigo Hermes faltantes</h2>';
    $cadena .= '<table border="1">';
    $cadena .= '<tr>';
    $cadena .= '<td><strong>Codigo Hermes</strong> </td>';
    $cadena .= '<td><strong>Nombre Material </strong></td>';
    $cadena .= '</tr>';
    foreach ($codigos_total_final as $codigos_finales_f) {
        $sql_update_material = mysql_query(" SELECT descripcion_material from material_apoyo where codigo_material = $codigos_finales_f  ");
        $nombre_material = mysql_result($sql_update_material, 0, 0);

        $cadena .= '<tr>';
        $cadena .= '<td>' . $codigos_finales_f . '</td>';
        $cadena .= '<td>' . $nombre_material . '</td>';
        $cadena .= '</tr>';
    }
    $cadena .= '</table>';
    $arr = array("cadena" => $cadena, "mensaje" => "lleno");
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