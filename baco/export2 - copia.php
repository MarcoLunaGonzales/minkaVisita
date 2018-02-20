<?php

set_time_limit(0);
require 'coneccion.php';
require("../conexion.inc");
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '16M');

$archivo = $_REQUEST["archivo"];
$filename = dirname(__FILE__) . '/uploads/' . $archivo;

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);

/* Hoja 1 */

$objPHPExcel->setActiveSheetIndex(0);
$rowIterator = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data = array();
foreach ($rowIterator as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; //skip first row
    $rowIndex = $row->getRowIndex();
    $array_data[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '');

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
        }
    }
}

/* Hoja 2 */

$objPHPExcel->setActiveSheetIndex(1);
$rowIterator2 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data2 = array();
foreach ($rowIterator2 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; //skip first row
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

/* Hoja 3 */

$objPHPExcel->setActiveSheetIndex(2);
$rowIterator3 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data3 = array();
foreach ($rowIterator3 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; //skip first row
    $rowIndex = $row->getRowIndex();
    $array_data3[$rowIndex] = array('A' => '', 'B' => '', 'C' => '', 'D' => '', 'E' => '', 'F' => '', 'G' => '', 'H' => '', 'I' => '', 'J' => '', 'K' => '');


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
        } else if ('J' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        } else if ('K' == $cell->getColumn()) {
            $array_data3[$rowIndex][$cell->getColumn()] = $cell->getCalculatedValue();
        }
    }
}

/* Hoja 4 */

$objPHPExcel->setActiveSheetIndex(3);
$rowIterator4 = $objPHPExcel->getActiveSheet()->getRowIterator();

$array_data4 = array();
foreach ($rowIterator4 as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false); // Loop all cells, even if it is not set
    if (1 == $row->getRowIndex())
        continue; //skip first row
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

/**/
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
    $sql_verificacion = mssql_query(" SELECT * FROM PRESENTACIONES_PRODUCTO WHERE cod_tipomercaderia = 5 AND  COD_HERMES = '$array_total_valor' ");
    $num = mssql_num_rows($sql_verificacion);
    if ($num == 0) {
        $codigos_total_final .= $array_total_valor . ",";
    }
}
if ($codigos_total_final == '') {

    /* Desde aqui para guardar */

    $sql_gestion = mssql_query("select * from GESTIONES where GESTION_ESTADO = 1");
    $codigo_gestion = mssql_result($sql_gestion, 0, 0);

    /* -----------------------------INGRESOS---------------------------------- */

    $codigo_salida_acondicionamiento = 0;
    $codigo_salida_venta = 0;
    $codigo_almacen_venta = 0;
    $codigo_almacen_venta_origen = 0;
    $codigo_tipo_ingreso_ventas = 0;
    $codigo_estado_ingreso_ventas = 0;
    $codigo_cliente_ingreso = 0;
    $codigo_ingreso_regional = 0;
    $codigo_salida_venta_origen = 0;
    $fecha_recepcion = '';
    $codigo_tipos_traspasos_por_ventas = 0;
    $sql_codigo_baco = mssql_query(" select max(COD_INGRESOVENTAS)  from ingresos_ventas");
    $codigo_ingreso_baco = mssql_result($sql_codigo_baco, 0, 0);
    $codigo_ingreso_baco++;


    $num_in = mssql_query(" select max(NRO_INGRESOVENTAS) from ingresos_ventas ");
    $num_ingreso_almacen = mssql_result($num_in, 0, 0);
    $num_ingreso_almacen++;

    $output_ingreso = "";
    foreach ($array_data as $hoja1 => $datos1) {
        $codigo_ingreso_hermes = $datos1['A'];
        $fecha_ingreso_hermes = $datos1['B'];
        $hora_ingreso_hermes = $datos1['C'];
        $nombre_tipo_ingreso_hermes = $datos1['D'];
        $observaciones_hermes = $datos1['E'];
        $numero_correlativo_hermes = $datos1['F'];
        $ingreso_anulado_hermes = $datos1['G'];
        /**/

        $oc = explode("/", $observaciones_hermes);
        $oc_limpio = preg_replace("/[^0-9]/", "", $oc[2]);
        if ($oc_limpio == '') {
            $oc_limpio = 0;
        } else {
            $oc_limpio = $oc_limpio;
        }

        /**/

        $fecha_final_ingreso_hermes = $fecha_ingreso_hermes . " " . $hora_ingreso_hermes . ".000";
        $fecha_modificacion_ingreso_ventas = $fecha_ingreso_hermes . " " . $hora_ingreso_hermes . ".000";
        $output_ingreso .= $codigo_gestion . "," . $codigo_ingreso_baco . "," . $codigo_salida_acondicionamiento . "," . $codigo_salida_venta . "," . $codigo_almacen_venta . ", " . $codigo_almacen_venta_origen
                . "," . $codigo_tipo_ingreso_ventas . "," . $num_ingreso_almacen . ",'" . $fecha_final_ingreso_hermes . "'," . $codigo_estado_ingreso_ventas . ",'" . $oc_limpio . "'," . $codigo_cliente_ingreso . ",'" .
                $fecha_modificacion_ingreso_ventas . "'," . $codigo_ingreso_regional . "," . $codigo_salida_venta_origen . ",'" . $fecha_recepcion . "'," . $codigo_tipos_traspasos_por_ventas . ",'" . $codigo_ingreso_hermes . "',";
        $codigo_ingreso_baco++;
        $num_ingreso_almacen++;
        $codigos_hermes .= $codigo_ingreso_hermes . ",";
    }
    $output_ingreso = substr($output_ingreso, 0, -1);
    $output_ingreso = explode(",", $output_ingreso);
    $output_ingreso = array_chunk($output_ingreso, 18);

    $veri_hermes = "";
    $sql_veri_hermes = mssql_query(" select cod_hermes from ingresos_ventas where COD_HERMES != null or COD_HERMES != 0 ");
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

        if (in_array($b[17], $veri_hermes)) {

            $query = "UPDATE INGRESOS_VENTAS SET COD_GESTION = $b[0], COD_INGRESOVENTAS = $b[1], COD_SALIDA_ACOND = $b[2],COD_SALIDAVENTA = $b[3],
                COD_ALMACEN_VENTA = $b[4],COD_ALMACEN_VENTAORIGEN = $b[5],COD_TIPOINGRESOVENTAS = $b[6],NRO_INGRESOVENTAS = $b[7],
                    FECHA_INGRESOVENTAS = $b[8],COD_ESTADO_INGRESOVENTAS = $b[9],OBS_INGRESOVENTAS = $b[10],COD_CLIENTE = $b[11],FECHAMODIFICACION_INGRESOSVENTAS = $b[12]
                ,COD_INGRESOREGIONAL = $b[13],COD_SALIDAVENTAORIGEN = $b[14],FECHA_RECEPCION = $b[15],COD_TIPOSTRANSPORTEVENTAS = $b[16]
                where cod_hermes = $b[17]";
            echo $query;
//            mssql_query($query);
        } else {
            $query = 'INSERT INTO INGRESOS_VENTAS (COD_GESTION,COD_INGRESOVENTAS,COD_SALIDA_ACOND,COD_SALIDAVENTA,COD_ALMACEN_VENTA,COD_ALMACEN_VENTAORIGEN
                ,COD_TIPOINGRESOVENTAS,NRO_INGRESOVENTAS,FECHA_INGRESOVENTAS,COD_ESTADO_INGRESOVENTAS,OBS_INGRESOVENTAS,COD_CLIENTE,FECHAMODIFICACION_INGRESOSVENTAS
                ,COD_INGRESOREGIONAL,COD_SALIDAVENTAORIGEN,FECHA_RECEPCION,COD_TIPOSTRANSPORTEVENTAS,COD_HERMES) VALUES (';
            foreach ($b as $c => $d) {
                $query .= $d . ",";
            }
            $query = substr($query, 0, -1);
            $query .= ");";
            echo $query;
//            mssql_query($query);
        }
    }


    $codigos_hermes = substr($codigos_hermes, 0, -1);

    $cod_ingreso_almacen_baco = mssql_query(" select max(COD_INGRESOVENTAS) from INGRESOS_DETALLEVENTAS ");
    $codigo_ingreso_detalle_baco = mssql_result($cod_ingreso_almacen_baco, 0, 0);
    $codigo_ingreso_detalle_baco++;
    
    $ver_codigo_ingreso_almacen = 0;

    $ingresos_detalle = "";
    $codigo_unidad_medida = 8;

    $codigo_presentacion_detalle = 0;
    $codigo_lote_produccion_detalle = 0;
    $cantidad_detalle = 0;
    $cantidad_unitaria_detalle = 0;
    $cantidad_restante_detalle = 0;
    $cantidad_unitaria_restante_detalle = 0;
    $codigo_tipo_observacion_ingreso_detalle = 0;
    $costo_almacen_detalle = 0;
    $fecha_vencimiento_detalle = 0;
    $observacion_ingreso_producto_detalle = 0;
    $costo_actualizado_detalle = 0;
    $costo_actualizado_final_detalle = 0;
    $fecha_actualizacion_detalle = 0;
    $cantidad_frv_detalle = 0;
    $cantidad_unitaria_frv_detalle = 0;
    $cantidad_mas_detalle = 0;
    $cantidad_unitaria_mas_detalle = 0;
    $cantidad_menos_detalle = 0;
    $cantidad_unitaria_menos_detalle = 0;
    $codigo_hermes_detalle = 0;
    
    foreach ($array_data2 as $hoja2 => $datos2) {
        $cod_ingreso_almacen = $datos2['A'];
        $cod_material = $datos2['B'];
        $nro_lote = $datos2['C'];
        $fecha_vencimiento = $datos2['D'];
        $cantidad_unitaria = $datos2['E'];
        $cantidad_restante = $datos2['F'];
        if ($ver_codigo_ingreso_almacen == $cod_ingreso_almacen) {
            $codigo_ingreso_detalle_baco--;
        }
        /**/

        $sql_cambio_cod_hermes = mssql_query(" select cod_material from materiales where cod_hermes = $cod_material ");
        $cod_nuevo_baco = mssql_result($sql_cambio_cod_hermes, 0, 0);

        /**/
        $ingresos_detalle .= $cod_nuevo_baco . "," . $codigo_ingreso_detalle_baco . "," . $cod_almacen . "," . 0 . "," . $cantidad_unitaria . "," . $cantidad_unitaria . "," . $codigo_unidad_medida . "," . 0 . "," . 0 . "," . 0 . ",'" . 0 . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cod_ingreso_almacen . ",";
        $codigo_ingreso_detalle_baco++;
        $ver_codigo_ingreso_almacen = $cod_ingreso_almacen;
    }

    $ingresos_detalle = substr($ingresos_detalle, 0, -1);
    $ingresos_detalle = explode(",", $ingresos_detalle);
    $ingresos_detalle = array_chunk($ingresos_detalle, 17);


    $sql_veri_detalle = mssql_query(" select cod_hermes from ingresos_almacen_detalle where cod_hermes != null or cod_hermes != 0 ");
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
//            mssql_query($query_detalle);
        } else {
            $query_detalle = "INSERT into INGRESOS_ALMACEN_DETALLE (COD_MATERIAL,COD_INGRESO_ALMACEN,COD_SECCION,NRO_UNIDADES_EMPAQUE,CANT_TOTAL_INGRESO,CANT_TOTAL_INGRESO_FISICO,
                                        COD_UNIDAD_MEDIDA,PRECIO_TOTAL_MATERIAL,PRECIO_UNITARIO_MATERIAL,COSTO_UNITARIO,observacion,PRECIO_NETO,COSTO_PROMEDIO,COSTO_UNITARIO_ACTUALIZADO,
                                        FECHA_ACTUALIZACION,COSTO_UNITARIO_ACTUALIZADO_FINAL,cod_hermes) VALUES (";
            foreach ($det as $ingre => $detalle) {
                $query_detalle .= $detalle . ",";
            }
            $query_detalle = substr($query_detalle, 0, -1);
            $query_detalle .= ");";
//            mssql_query($query_detalle);
        }
    }

    /*
     * COD_MATERIAL, COD_INGRESO_ALMACEN, COD_SECCION, NRO_UNIDADES_EMPAQUE, CANT_TOTAL_INGRESO, CANT_TOTAL_INGRESO_FISICO, COD_UNIDAD_MEDIDA, PRECIO_TOTAL_MATERIAL
     * PRECIO_UNITARIO_MATERIAL, COSTO_UNITARIO, observacion, PRECIO_NETO, COSTO_UNITARIO_ACTUALIZADO, COSTO_PROMEDIO, FECHA_ACTUALIZACION, COSTO_UNITARIO_ACTUALIZADO_FINAL
     *  */

//    $ingresos_detalles = "";
//
//    foreach ($array_data2 as $hoja2a => $datos2a) {
//        $cod_ingreso_almacen_s = $datos2a['A'];
//        $cod_material_s = $datos2a['B'];
//        $nro_lote_s = $datos2a['C'];
//        $fecha_vencimiento_s = $datos2a['D'];
//        $cantidad_unitaria_s = $datos2a['E'];
//        $cantidad_restante_s = $datos2a['F'];
//        /**/
//
//        $sql_cambio_cod_hermes_s = mssql_query(" select cod_material from materiales where cod_hermes = $cod_material_s ");
//        $cod_nuevo_baco_s = mssql_result($sql_cambio_cod_hermes_s, 0, 0);
//
//        /**/
//        $ingresos_detalles .= $cod_nuevo_baco_s . "," . $codigo_ingreso_detalle_baco . "," . $cod_almacen . "," . 0 . "," . $cantidad_unitaria_s . "," . $cantidad_unitaria_s . "," . $codigo_unidad_medida . "," . 0 . "," . 0 . "," . 0 . ",'" . 0 . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cod_ingreso_almacen_s . ",";
//        $codigo_ingreso_detalle_baco++;
//    }
//
//    $ingresos_detalles = substr($ingresos_detalles, 0, -1);
//    $ingresos_detalles = explode(",", $ingresos_detalles);
//    $ingresos_detalles = array_chunk($ingresos_detalles, 17);
//
//    $sql_veri_detalle_aux = mssql_query(" select cod_hermes from ingresos_almacen_detalle_auxiliar where cod_hermes != null or cod_hermes != 0 ");
//    if (mssql_num_rows($sql_veri_detalle_aux) == 0) {
//        
//    } else {
//        while ($row_cod_ingreso_detalle_aux = mssql_fetch_array($sql_veri_detalle_aux)) {
//            $veri_cod_detalle_aux .= $row_cod_ingreso_detalle_aux[0] . ",";
//        }
//        $veri_cod_detalle_aux = substr($veri_cod_detalle_aux, 0, -1);
//        $veri_cod_detalle_aux = explode(",", $veri_cod_detalle_aux);
//    }
//    foreach ($ingresos_detalles as $ings => $dets) {
//        if (in_array($dets[16], $veri_cod_detalle_aux)) {
//            $query_detalles = " Update ingresos_almacen_detalle_auxiliar set cod_material = $dets[0] , cod_ingreos_almacen = $dets[1] where cod_hermes = $dets[16]  ";
//            mssql_query($query_detalles);
//        } else {
//            $query_detalles = "INSERT INTO INGRESOS_ALMACEN_DETALLE_AUXILIAR (COD_MATERIAL,COD_INGRESO_ALMACEN,COD_SECCION,NRO_UNIDADES_EMPAQUE,CANT_TOTAL_INGRESO,CANT_TOTAL_INGRESO_FISICO
//                ,COD_UNIDAD_MEDIDA,PRECIO_TOTAL_MATERIAL,PRECIO_UNITARIO_MATERIAL,COSTO_UNITARIO,observacion,PRECIO_NETO,COSTO_UNITARIO_ACTUALIZADO,COSTO_PROMEDIO
//                ,FECHA_ACTUALIZACION,COSTO_UNITARIO_ACTUALIZADO_FINAL,cod_hermes) VALUES(";
//            foreach ($dets as $ingres => $detalles) {
//                $query_detalles .= $detalles . ",";
//            }
//            $query_detalles = substr($query_detalles, 0, -1);
//            $query_detalles .= ");";
//            mssql_query($query_detalles);
//        }
//    }

    /**/

    /* -----------------------------FIN INGRESOS---------------------------- */

    /* -----------------------------SALIDAS------------------------------------- */

//    $codigo_almacen = "2";
//    $cod_almacen_salida = "2";
//    $estado_salida_sitema = "1";
//    $codigo_tipo_salida = "1";
//    $cod_estado_salida_almacen = "1";
//    $sql_codigo_baco = mssql_query(" select max(cod_salida_almacen) from salidas_almacen ");
//    $codigo_baco_salida = mssql_result($sql_codigo_baco, 0, 0);
//    $codigo_baco_salida++;
//
//    $sql_codigo_n_salida_almacen = mssql_query(" select max(nro_salida_almacen) from salidas_almacen ");
//    $codigo_n_salida_almacen = mssql_result($sql_codigo_n_salida_almacen, 0, 0);
//    $codigo_n_salida_almacen++;
//
//    foreach ($array_data3 as $hoja3 => $datos_s3) {
//        $codigo_salida_hermes = $datos_s3['A'];
//        $fecha = $datos_s3['B'];
//        $hora = $datos_s3['C'];
//        $nombre_salida = $datos_s3['D'];
//        $observaciones = $datos_s3['E'];
//        $nota_entrega = $datos_s3['F'];
//        $n_correlativo = $datos_s3['G'];
//        $salida_anulada = $datos_s3['H'];
//        $fecha = $fecha . " " . $hora . ".000";
//        $sql_registros_salidas = mysql_query("select * from salida_almacenes where cod_salida_almacenes = $codigo_salida_hermes ");
//        while ($row_salidas = mysql_fetch_array($sql_registros_salidas)) {
//            $estado_salida = $row_salidas[8];
//        }
//        $output_salida .= $codigo_gestion . "," . $codigo_baco_salida . "," . 0 . "," . 0 . "," . 0 . "," . $codigo_tipo_salida . "," . $cod_almacen_salida . "," . $codigo_n_salida_almacen . ",'" . $fecha . "','" . $observaciones . "'," . $estado_salida_sitema . "," . $codigo_almacen . "," . 0 . "," . 0 . "," . $cod_estado_salida_almacen . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $codigo_salida_hermes . ",";
//        $codigo_baco_salida++;
//        $codigo_n_salida_almacen++;
//        $codigos_salida_hermes .= $codigo_salida_hermes . ",";
//    }
//
//    $output_salida = substr($output_salida, 0, -1);
//    $output_salida = explode(",", $output_salida);
//    $output_salida = array_chunk($output_salida, 21);
//    /**/
//
//    $veri_hermes_salida = "";
//    $sql_veri_hermes_salida = mssql_query(" SELECT codigo_hermes from SALIDAS_ALMACEN where codigo_hermes != null or codigo_hermes != 0 ");
//    if (mssql_num_rows($sql_veri_hermes_salida) == 0) {
//        
//    } else {
//        while ($row_veri_hermes_salida = mssql_fetch_array($sql_veri_hermes_salida)) {
//            $veri_hermes_salida .= $row_veri_hermes_salida[0] . ",";
//        }
//        $veri_hermes_salida = substr($veri_hermes_salida, 0, -1);
//        $veri_hermes_salida = explode(",", $veri_hermes_salida);
//    }
//
//    foreach ($output_salida as $aa => $bb) {
//        if (in_array($bb[20], $veri_hermes_salida)) {
//            $query2 = " UPDATE SALIDAS_ALMACEN set  COD_GESTION = $bb[0], COD_TIPO_SALIDA_ALMACEN = $bb[5], COD_AREA_EMPRESA = $bb[6],
//                             NRO_SALIDA_ALMACEN = $bb[7], FECHA_SALIDA_ALMACEN = $bb[8], OBS_SALIDA_ALMACEN = $bb[9], ESTADO_SISTEMA = $bb[10], COD_ALMACEN = $bb[11], 
//                             COD_ESTADO_SALIDA_ALMACEN = $bb[14] where codigo_hermes = $bb[20]  ";
//            mssql_query($query2);
//        } else {
//            $query2 = "insert into salidas_almacen (COD_GESTION,COD_SALIDA_ALMACEN,COD_ORDEN_PESADA,COD_FORM_SALIDA,COD_PROD,COD_TIPO_SALIDA_ALMACEN,COD_AREA_EMPRESA,NRO_SALIDA_ALMACEN,FECHA_SALIDA_ALMACEN,
//                        OBS_SALIDA_ALMACEN, ESTADO_SISTEMA, COD_ALMACEN, COD_ORDEN_COMPRA, COD_PERSONAL, COD_ESTADO_SALIDA_ALMACEN, COD_LOTE_PRODUCCION, COD_ESTADO_SALIDA_COSTO, cod_prod_ant,
//                        orden_trabajo, COD_PRESENTACION, codigo_hermes) VALUES (";
//            foreach ($bb as $cc => $dd) {
//                $query2 .= $dd . ",";
//            }
//            $query2 = substr($query2, 0, -1);
//            $query2 .= ");";
//            mssql_query($query2);
//        }
//    }

    /* DETALLES */

//    $codigos_salida_hermes = substr($codigos_salida_hermes, 0, -1);
//    $cod_salida_almacen_baco = mssql_query(" select max(COD_SALIDA_ALMACEN) from SALIDAS_ALMACEN_DETALLE ");
//    $codigo_salida_detalle_baco = mssql_result($cod_salida_almacen_baco, 0, 0);
//    $codigo_salida_detalle_baco++;
//    $ver_salida_detalle_baco = 0;
//
//    $salidas_detalle = "";
//
//    foreach ($array_data4 as $hoja4 => $datos4) {
//        $cod_salida_almacen = $datos4['A'];
//        $cod_material = $datos4['B'];
//        $cantidad_unitaria = $datos4['C'];
//        $observaciones = $datos4['D'];
//        if ($cod_salida_almacen == $ver_salida_detalle_baco) {
//            $codigo_salida_detalle_baco--;
//        }
//        /**/
//
//        $sql_cambio_cod_hermes_salida = mssql_query(" select cod_material from materiales where cod_hermes = $cod_material ");
//        $cod_nuevo_baco_salida = mssql_result($sql_cambio_cod_hermes_salida, 0, 0);
//
//        /**/
//        $salidas_detalle .= $codigo_salida_detalle_baco . "," . $cod_nuevo_baco_salida . "," . $cod_nuevo_baco_salida . "," . $codigo_unidad_medida . "," . 0 . "," . $cod_salida_almacen . ",";
//        $codigo_salida_detalle_baco++;
//        $ver_salida_detalle_baco = $cod_salida_almacen;
//    }
//
//    $salidas_detalle = substr($salidas_detalle, 0, -1);
//    $salidas_detalle = explode(",", $salidas_detalle);
//    $salidas_detalle = array_chunk($salidas_detalle, 6);
//
//    $sql_veri_detalle_salida = mssql_query(" select cod_hermes from salidas_almacen_detalle where cod_hermes != null or cod_hermes != 0 ");
//    if (mssql_num_rows($sql_veri_detalle_salida) == 0) {
//        
//    } else {
//        while ($row_cod_salida_detalle = mssql_fetch_array($sql_veri_detalle_salida)) {
//            $veri_cod_detalle_salida .= $row_cod_salida_detalle[0] . ",";
//        }
//        $veri_cod_detalle_salida = substr($veri_cod_detalle_salida, 0, -1);
//        $veri_cod_detalle_salida = explode(",", $veri_cod_detalle_salida);
//    }
//
//    foreach ($salidas_detalle as $sal => $dett) {
//        if (in_array($dett[5], $veri_cod_detalle_salida)) {
//            $query_detalle = "UPDATE salidas_almacen_detalle set   COD_MATERIAL = $dett[1] where cod_hermes  = $dett[5] ";
//            mssql_query($query_detalle);
//        } else {
//            $query_detalle = "INSERT into salidas_almacen_detalle (COD_SALIDA_ALMACEN,COD_MATERIAL,CANTIDAD_SALIDA_ALMACEN,COD_UNIDAD_MEDIDA,COD_ESTADO_MATERIAL,cod_hermes) VALUES (";
//            foreach ($dett as $ingres => $detallee) {
//                $query_detalle .= $detallee . ",";
//            }
//            $query_detalle = substr($query_detalle, 0, -1);
//            $query_detalle .= ");";
//            mssql_query($query_detalle);
//        }
//    }

    /*
     * COD_SALIDA_ALMACEN,COD_MATERIAL,COD_INGRESO_ALMACEN,ETIQUETA,COSTO_SALIDA,FECHA_VENCIMIENTO,CANTIDAD,COSTO_SALIDA_ACTUALIZADO,
     * FECHA_ACTUALIZACION,COSTO_SALIDA_ACTUALIZADO_FINAL
     */

//    $salidas_detalles = "";
//
//    foreach ($array_data4 as $hoja4a => $datos4a) {
//        $cod_salida_almacen_s = $datos4a['A'];
//        $cod_material_salida_s = $datos4a['B'];
//        $cantidad_unitaria_s = $datos4a['C'];
//        $observaciones_s = $datos4a['D'];
//        /**/
//
//        $sql_cambio_cod_hermes_salida_s = mssql_query(" select cod_material from materiales where cod_hermes = $cod_material_salida_s ");
//        $cod_nuevo_baco_salida_s = mssql_result($sql_cambio_cod_hermes_salida_s, 0, 0);
//
//        /**/
//        $salidas_detalles .= $codigo_salida_detalle_baco . "," . $cod_nuevo_baco_salida_s . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cantidad_unitaria_s . "," . 0 . "," . 0 . "," . 0 . "," . $cod_salida_almacen_s . ",";
//        $codigo_salida_detalle_baco++;
//    }
//
//    $salidas_detalles = substr($salidas_detalles, 0, -1);
//    $salidas_detalles = explode(",", $salidas_detalles);
//    $salidas_detalles = array_chunk($salidas_detalles, 11);
//
//    $sql_veri_detalle_aux_s = mssql_query(" select cod_hermes from SALIDAS_ALMACEN_DETALLE_INGRESO where cod_hermes != null or cod_hermes != 0 ");
//    if (mssql_num_rows($sql_veri_detalle_aux_s) == 0) {
//        
//    } else {
//        while ($row_cod_ingreso_detalle_aux_s = mssql_fetch_array($sql_veri_detalle_aux_s)) {
//            $veri_cod_detalle_aux_s .= $row_cod_ingreso_detalle_aux_s[0] . ",";
//        }
//        $veri_cod_detalle_aux_s = substr($veri_cod_detalle_aux_s, 0, -1);
//        $veri_cod_detalle_aux_s = explode(",", $veri_cod_detalle_aux_s);
//    }
//    foreach ($salidas_detalles as $ings_s => $dets_s) {
//        if (in_array($dets_s[16], $veri_cod_detalle_aux_s)) {
//            $query_detalles_s = " UPDATE SALIDAS_ALMACEN_DETALLE_INGRESO SET cod_salida_almacen = $dets_s[0], cod_material = $dets_s[1] where cod_hermes = $dets_s[10]  ";
//            mssql_query($query_detalles_s);
//        } else {
//            $query_detalles_s = "INSERT INTO SALIDAS_ALMACEN_DETALLE_INGRESO (COD_SALIDA_ALMACEN,COD_MATERIAL, COD_INGRESO_ALMACEN, ETIQUETA, COSTO_SALIDA,FECHA_VENCIMIENTO,
//                CANTIDAD,COSTO_SALIDA_ACTUALIZADO,FECHA_ACTUALIZACION,COSTO_SALIDA_ACTUALIZADO_FINAL,cod_hermes) VALUES(";
//            foreach ($dets_s as $ingres_s => $detalles_s) {
//                $query_detalles_s .= $detalles_s . ",";
//            }
//            $query_detalles_s = substr($query_detalles_s, 0, -1);
//            $query_detalles_s .= ");";
//            mssql_query($query_detalles_s);
//        }
//    }

    /**/

    $cadena = "";
    $cadena .= '<h2>Todos los materiales tienen su codigo hermes respectivo</h2>';
    $cadena .= '<h3>Los datos se guardaron en la base de datos de ZEUS</h3>';
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
        $sql_update_material = mysql_query(" select descripcion from muestras_medicas where codigo = '$codigos_finales_f'  ");
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