<?php

set_time_limit(0);
require 'coneccion.php';
require("../conexion.inc");
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '132M');

$archivo = $_REQUEST["archivo"];
$filename = dirname(__FILE__) . '/uploads/' . $archivo;

$objReader = new PHPExcel_Reader_Excel5();
$objReader->setReadDataOnly(true);
$objPHPExcel = $objReader->load($filename);


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
//    echo (" SELECT * FROM PRESENTACIONES_PRODUCTO WHERE cod_tipomercaderia = 5 AND  COD_HERMES = '$array_total_valor' ") . "<br />";
    $num = mssql_num_rows($sql_verificacion);
    if ($num == 0) {
        $codigos_total_final .= $array_total_valor . ",";
    }
}
if ($codigos_total_final == '') {

    /* Desde aqui para guardar */

    $sql_gestion = mssql_query("select * from GESTIONES where GESTION_ESTADO = 1");
    $codigo_gestion = mssql_result($sql_gestion, 0, 0);
    mssql_query("SET DATEFORMAT ymd");

    /* -----------------------------SALIDAS------------------------------------- */

    $codigo_gestion_salida_venta = $codigo_gestion;
    $codigo_almacen_venta_salida = 32;
    $codigo_almacen_venta_destino_salida = 0;
    $fecha_salida_venta = '';
    $codigo_salida_estado_venta = 1;
    $codigo_tipo_doc_venta = 1;
    $codigo_tipo_salida_ventas = 4;
    $codigo_tipo_venta_salida =0;
    $codigo_pedida_venta_salida =0;
    $codigo_cliente = 0;
    $observacion_salida_venta = '';
    $monto_total_salida_venta =0;
    $porcentaje_descuento_salida_venta =0;
    $codigo_salida_promocional_venta =0;
    $monto_cancelado_salida_venta =0;
    $codigo_personal_salida_venta =0;
    $salida_envio_correo_venta = '';
    $fecha_modificacion_salida_venta ='';
    $codigo_salida_regional_venta = 0;
    $codigo_tipo_solicitud_venta = 0;
    $codigo_campania = 0;
    $fecha_salida_despacho_venta = '';
    $porcentaje_descuento_preferencial_venta = 0;
    $codigo_salida_venta_origen = 0;
    $codigo_area_emresa_venta = 1;
    
    $sql_codigo_zeus = mssql_query(" select max(COD_SALIDAVENTA) from SALIDAS_VENTAS ");
    $codigo_zeus_salida = mssql_result($sql_codigo_zeus, 0, 0);
    $codigo_zeus_salida++;

    $sql_codigo_n_salida_ventas = mssql_query(" select max(NRO_SALIDAVENTA) from SALIDAS_VENTAS where COD_ALMACEN_VENTA = 32 and COD_GESTION = $codigo_gestion ");
    $numero_salida_venta = mssql_result($sql_codigo_n_salida_ventas, 0, 0);
    $numero_salida_venta++;
    
    $sql_numero_factura = mssql_query(" select max(NRO_FACTURA) from SALIDAS_VENTAS where COD_ALMACEN_VENTA = 32 and COD_GESTION = $codigo_gestion ");
    $numero_factura_venta_salida = mssql_result($sql_numero_factura, 0, 0);
    $numero_factura_venta_salida++;
    
    foreach ($array_data3 as $hoja3 => $datos_s3) {
        $codigo_salida_hermes = $datos_s3['A'];
        $fecha = $datos_s3['B'];
        $hora = $datos_s3['C'];
        $nombre_salida = $datos_s3['D'];
        $descripcion = $datos_s3['E'];
        $nombre_almacen = $datos_s3['F'];
        $observacion_salida_venta = $datos_s3['G'];
        $estado_salida = $datos_s3['H'];
        $numero_correlativo = $datos_s3['I'];
        $salida_anulada = $datos_s3['J'];
        $codigo_almacen_venta_destino_salida = $datos_s3['K'];
        $fecha_salida_venta = $fecha . " " . $hora . ".000";
        $fecha_salida_solicitud_venta = $fecha . " " . $hora . ".000";
        
        $output_salida .= $codigo_gestion_salida_venta ."," . $codigo_zeus_salida . "," . $codigo_almacen_venta_salida . "," . $codigo_almacen_venta_destino_salida . "," . $numero_salida_venta. ",'" .
                $fecha_salida_venta . "'," . $codigo_salida_estado_venta . "," . $codigo_tipo_doc_venta . "," . $numero_factura_venta_salida . "," . $codigo_tipo_salida_ventas . "," . $codigo_tipo_venta_salida . "," .
                $codigo_pedida_venta_salida . "," . $codigo_cliente . ",'" . $observacion_salida_venta . "'," . $monto_total_salida_venta . "," . $porcentaje_descuento_salida_venta . "," . $codigo_salida_promocional_venta . "," .
                $monto_cancelado_salida_venta . "," . $codigo_personal_salida_venta . ",'" . $salida_envio_correo_venta . "','" . $fecha_modificacion_salida_venta . "'," . $codigo_salida_regional_venta . ",'" . $fecha_salida_solicitud_venta . "'," .
                $codigo_tipo_solicitud_venta . ",'" . $fecha_salida_despacho_venta . "'," .  $codigo_campania . "," . $porcentaje_descuento_preferencial_venta . "," . $codigo_salida_venta_origen . "," . $codigo_area_emresa_venta . "," .
                $codigo_salida_hermes . ",";
        $codigo_zeus_salida++;
        $numero_factura_venta_salida++;
        $numero_salida_venta++;
        $codigo_salida_hermes .= $codigo_salida_hermes . ",";
    }

    $output_salida = substr($output_salida, 0, -1);
    $output_salida = explode(",", $output_salida);
    $output_salida = array_chunk($output_salida, 30);
    
    /**/

    $veri_hermes_salida = "";
    $sql_veri_hermes_salida = mssql_query("SELECT codigo_hermes from SALIDAS_VENTAS where codigo_hermes != null or codigo_hermes != 0");
    if (mssql_num_rows($sql_veri_hermes_salida) == 0) {
        
    } else {
        while ($row_veri_hermes_salida = mssql_fetch_array($sql_veri_hermes_salida)) {
            $veri_hermes_salida .= $row_veri_hermes_salida[0] . ",";
        }
        $veri_hermes_salida = substr($veri_hermes_salida, 0, -1);
        $veri_hermes_salida = explode(",", $veri_hermes_salida);
    }

    foreach ($output_salida as $aa => $bb) {
        if (in_array($bb[29], $veri_hermes_salida)) {
            $query2 = "UPDATE SALIDAS_VENTAS SET  COD_GESTION = $bb[0] , COD_SALIDAVENTA = $bb[1], COD_ALMACEN_VENTA = $bb[2] , COD_ALMACEN_VENTADESTINO = $bb[3],
            NRO_SALIDAVENTA = $bb[4], FECHA_SALIDAVENTA = $bb[5], COD_ESTADO_SALIDAVENTA = $bb[6], COD_TIPODOC_VENTA = $bb[7], NRO_FACTURA = $bb[8], COD_TIPOSALIDAVENTAS=$bb[9],
            COD_TIPOVENTA=$bb[10],COD_PEDIDOVENTA=$bb[11],COD_CLIENTE=$bb[12],OBS_SALIDAVENTA=$bb[13],MONTO_TOTAL=$bb[14],PORCENTAJE_DESCUENTO=$bb[15],COD_SALIDA_MAT_PROMOCIONAL=$bb[16],
            MONTO_CANCELADO=$bb[17],COD_PERSONAL=$bb[18],SALIDA_ENVIO_CORREO=$bb[19],FECHAMODIFICACION_SALIDAVENTA=$bb[20],COD_SALIDAREGIONAL=$bb[21],
            FECHA_SALIDASOLICITUD=$bb[22],COD_TIPO_SOLICITUD_VENTA=$bb[23],FECHA_SALIDADESPACHO=$bb[24],COD_CAMPANIA=$bb[25],PORCENTAJE_DESCUENTO_PREFERENCIAL=$bb[26],
            COD_SALIDAVENTAORIGEN=$bb[27],COD_AREA_EMPRESA=$bb[28] where codigo_hermes = $bb[29]";
            mssql_query($query2);
        } else {
            $query2 = "INSERT INTO SALIDAS_VENTAS (COD_GESTION,COD_SALIDAVENTA,COD_ALMACEN_VENTA,COD_ALMACEN_VENTADESTINO,NRO_SALIDAVENTA,FECHA_SALIDAVENTA
                ,COD_ESTADO_SALIDAVENTA,COD_TIPODOC_VENTA,NRO_FACTURA,COD_TIPOSALIDAVENTAS,COD_TIPOVENTA,COD_PEDIDOVENTA,COD_CLIENTE,OBS_SALIDAVENTA,
                MONTO_TOTAL,PORCENTAJE_DESCUENTO,COD_SALIDA_MAT_PROMOCIONAL,MONTO_CANCELADO,COD_PERSONAL,SALIDA_ENVIO_CORREO,FECHAMODIFICACION_SALIDAVENTA,
                COD_SALIDAREGIONAL,FECHA_SALIDASOLICITUD,COD_TIPO_SOLICITUD_VENTA,FECHA_SALIDADESPACHO,COD_CAMPANIA,PORCENTAJE_DESCUENTO_PREFERENCIAL,
                COD_SALIDAVENTAORIGEN,COD_AREA_EMPRESA,codigo_hermes) VALUES (";
            foreach ($bb as $cc => $dd) {
                $query2 .= $dd . ",";
            }
            $query2 = substr($query2, 0, -1);
            $query2 .= ");";
            mssql_query($query2);
        }
    }

    /* DETALLES */

    $codigos_salida_hermes = substr($codigos_salida_hermes, 0, -1);
    $cod_salida_almacen_zeus = mssql_query(" select max(COD_SALIDAVENTAS) from SALIDAS_DETALLEVENTAS ");
    $codigo_salida_detalle_zeus = mssql_result($cod_salida_almacen_zeus, 0, 0);
    $codigo_salida_detalle_zeus++;
    
    $ver_salida_detalle_baco = 0;
    $salidas_detalle = "";

    foreach ($array_data4 as $hoja4 => $datos4) {
        $cod_salida_detalle_venta = $datos4['A'];
        $cod_material_detalle_venta = $datos4['B'];
        $cantidad_unitaria_detalle_venta = $datos4['C'];
        $observaciones_detalle_venta = $datos4['D'];
        if ($cod_salida_detalle_venta == $ver_salida_detalle_baco) {
            $codigo_salida_detalle_zeus--;
        }
        /**/
        $codigo_salida_ventas = $codigo_salida_detalle_zeus;
        $codigo_lote_produccion = '';
        $fecha_vencimiento = '';
        $cantidad = $cantidad_unitaria_detalle_venta;
        $cantidad_unitaria = $cantidad;
        $cantidad_bonificacion = 0;
        $cantidad_unitaria_bonificacion = 0;
        $cantidad_total = $cantidad_unitaria_detalle_venta;
        $cantidad_unitaria_total= 0;
        $porcentaje_aplicado_precio = 0;
        $precio_lista = 0;
        $precio_venta =0;
        $costo_almacen =0;
        $costo_actualizado = 0;
        $costo_actualizado_final = 0;
        $fecha_actualizacion = '';
        $codigo_area_empresa_detalle = 1;
        $codigo_oferta =0;
        $codigo_hermes_detalle_salida = $cod_salida_detalle_venta;
        $sql_cambio_cod_hermes_salida = mssql_query(" select cod_presentacion from PRESENTACIONES_PRODUCTO where cod_hermes = '$cod_material_detalle_venta' and cod_tipomercaderia=5 ");
        $codigo_presentacion = mssql_result($sql_cambio_cod_hermes_salida, 0, 0);

        /**/
        $salidas_detalle .= $codigo_salida_ventas . "," . $codigo_presentacion . ",'" . $codigo_lote_produccion . "','" . $fecha_vencimiento . "'," . $cantidad . "," . $cantidad_unitaria . "," . $cantidad_bonificacion . "," .
           $cantidad_unitaria_bonificacion . "," . $cantidad_total . "," . $cantidad_unitaria_total . "," . $porcentaje_aplicado_precio . "," . $precio_lista . "," . $precio_venta . "," . $costo_almacen . "," .
           $costo_actualizado . "," . $costo_actualizado_final . ",'" . $fecha_actualizacion . "',". $codigo_oferta . "," . $codigo_area_empresa_detalle  . ",'" . $codigo_hermes_detalle_salida . "',";
        $codigo_salida_detalle_zeus++;
        $ver_salida_detalle_baco = $cod_salida_detalle_venta;
    }
//
    $salidas_detalle = substr($salidas_detalle, 0, -1);
    $salidas_detalle = explode(",", $salidas_detalle);
    $salidas_detalle = array_chunk($salidas_detalle, 20);

    $sql_veri_detalle_salida = mssql_query(" select codigo_hermes from SALIDAS_DETALLEVENTAS where codigo_hermes != null or codigo_hermes != 0 ");
    if (mssql_num_rows($sql_veri_detalle_salida) == 0) {
        
    } else {
        while ($row_cod_salida_detalle = mssql_fetch_array($sql_veri_detalle_salida)) {
            $veri_cod_detalle_salida .= $row_cod_salida_detalle[0] . ",";
        }
        $veri_cod_detalle_salida = substr($veri_cod_detalle_salida, 0, -1);
        $veri_cod_detalle_salida = explode(",", $veri_cod_detalle_salida);
    }

    foreach ($salidas_detalle as $sal => $dett) {
        if (in_array($dett[19], $veri_cod_detalle_salida)) {
            $query_detalle = "UPDATE SALIDAS_DETALLEVENTAS set COD_SALIDAVENTAS = $dett[0],COD_PRESENTACION = $dett[1],COD_LOTE_PRODUCCION = $dett[2],FECHA_VENC = $dett[3],CANTIDAD = $dett[4],CANTIDAD_UNITARIA = $dett[5],CANTIDAD_BONIFICACION = $dett[6],
                CANTIDAD_UNITARIABONIFICACION = $dett[7],CANTIDAD_TOTAL = $dett[8],CANTIDAD_UNITARIATOTAL = $dett[9],PORCENTAJE_APLICADOPRECIO = $dett[10],PRECIO_LISTA = $dett[11],PRECIO_VENTA = $dett[12],COSTO_ALMACEN = $dett[13],COSTO_ACTUALIZADO = $dett[14],
                COSTO_ACTUALIZADO_FINAL = $dett[15],FECHA_ACTUALIZACION = $dett[16],COD_OFERTA  = $dett[17], COD_AREA_EMPRESA = $dett[18] where codigo_hermes = $dett[19]";
            mssql_query($query_detalle);
        } else {
            $query_detalle = "INSERT into SALIDAS_DETALLEVENTAS (COD_SALIDAVENTAS,COD_PRESENTACION,COD_LOTE_PRODUCCION,FECHA_VENC,CANTIDAD,CANTIDAD_UNITARIA,CANTIDAD_BONIFICACION,
                CANTIDAD_UNITARIABONIFICACION,CANTIDAD_TOTAL,CANTIDAD_UNITARIATOTAL,PORCENTAJE_APLICADOPRECIO,PRECIO_LISTA,PRECIO_VENTA,COSTO_ALMACEN,COSTO_ACTUALIZADO,
                COSTO_ACTUALIZADO_FINAL,FECHA_ACTUALIZACION,COD_OFERTA,COD_AREA_EMPRESA,codigo_hermes) 
                VALUES (";
            foreach ($dett as $ingres => $detallee) {
                $query_detalle .= $detallee . ",";
            }
            $query_detalle = substr($query_detalle, 0, -1);
            $query_detalle .= ");";
//            echo $query_detalle;
            mssql_query($query_detalle);
        }
    }

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
    $cadena .= '<h2>Todas las muestras tienen su codigo hermes respectivo</h2>';
    $cadena .= '<h3>Los datos se guardaron en la base de datos de ZEUS</h3>';
    $arr = array("mensaje" => "vacio", "cadena" => $cadena);

    echo json_encode($arr);


    /* Fin para guardar */
} else {

    $codigos_total_final = substr($codigos_total_final, 0, -1);
    $codigos_total_final = explode(",", $codigos_total_final);

    $cadena = "";
    $cadena .= '<h2>Muestras con codigo Hermes faltantes</h2>';
    $cadena .= '<table border="1">';
    $cadena .= '<tr>';
    $cadena .= '<td><strong>Codigo Hermes</strong> </td>';
    $cadena .= '<td><strong>Nombre Material </strong></td>';
    $cadena .= '</tr>';
    foreach ($codigos_total_final as $codigos_finales_f) {
        $sql_update_material = mysql_query(" select CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$codigos_finales_f'  ");
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