<?php
set_time_limit(0);
require 'coneccion.php';
require("../conexion.inc");

$fecha_inicio = $_REQUEST['fecha_inicio'];
$fecha_final = $_GET['fecha_final'];

$sql_insert = "";
$sql_insert_sql = "";

$sql_gestion = mssql_query("select * from GESTIONES where GESTION_ESTADO = 1");
$codigo_gestion = mssql_result($sql_gestion, 0, 0);


/* -----------------------------INGRESOS---------------------------------- */

$cod_tipo_inreso_almacen = "1";
$cod_estado_ingreso_almacen = "1";
$cod_almacen = "2";
$estado_sistema = "1";

$sql_ingresos = mysql_query(" select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado , i.cod_tipoingreso
    FROM ingreso_almacenes i, tipos_ingreso ti where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='1000' and i.grupo_ingreso=2 and
    i.fecha < '$fecha_final' and i.fecha > '$fecha_inicio' order by i.nro_correlativo desc  ");

$sql_codigo_baco = mssql_query(" select max(cod_ingreso_almacen)  from ingresos_almacen");
$codigo_baco_ing = mssql_result($sql_codigo_baco, 0, 0);
$codigo_baco_ing++;

while ($row = mysql_fetch_array($sql_ingresos)) {
    $codigo_hermes = $row[0];
    $fecha = $row[1];
    $hora = $row[2];
    $nombre = $row[3];
    $obs = $row[4];
    $nota = $row[5];
    $n_correlativo = $row[6];
    $anulado = $row[7];
    $codigo_tipo_ingreso = $row[8];
    $fechaa = $fecha . " " . $hora;
    $output_ingreso .= $codigo_baco_ing . "," . $cod_tipo_inreso_almacen . "," . 0 . "," . $codigo_gestion . "," . $cod_estado_ingreso_almacen . "," . 0 . ",'" . $fechaa . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . ",'" . $obs . "'," . 0 . "," . 0 . "," . $estado_sistema . "," . 0 . "," . $cod_almacen . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $codigo_hermes . ",";
    $codigo_baco_ing++;
    $codigos_hermes .= $codigo_hermes . ",";
}

$output_ingreso = substr($output_ingreso, 0, -1);
$output_ingreso = explode(",", $output_ingreso);
$output_ingreso = array_chunk($output_ingreso, 23);

/**/
$veri_hermes = "";
$sql_veri_hermes = mssql_query(" select codigo_hermes from ingresos_almacen where codigo_hermes != null or codigo_hermes != 0 ");
if (mssql_num_rows($sql_veri_hermes) == 0) {
    
} else {
    while ($row_veri_hermes = mssql_fetch_array($sql_veri_hermes)) {
        $veri_hermes .= $row_veri_hermes[0] . ",";
    }
    $veri_hermes = substr($veri_hermes, 0, -1);
    $veri_hermes = explode(",", $veri_hermes);
}
foreach ($output_ingreso as $a => $b) {

    if (in_array($b[22], $veri_hermes)) {
        $query = "UPDATE INGRESOS_ALMACEN set cod_tipo_ingreso_almacen = $b[1], cod_orden_compra = $b[2], cod_gestion = $b[3], cod_estado_ingreso_almacen = $b[4], cod_devolucion = $b[5],
                          FECHA_INGRESO_ALMACEN = $b[6],COD_TIPO_DOCUMENTO = $b[7], NRO_DOCUMENTO = $b[8],  FECHA_DOCUMENTO = $b[9], CREDITO_FISCAL_SI_NO = $b[10], OBS_INGRESO_ALMACEN = $b[11], 
                          NRO_INGRESO_ALMACEN = $b[12], COD_PROVEEDOR = $b[13], ESTADO_SISTEMA = $b[14], COD_TIPO_COMPRA = $b[15], COD_ALMACEN = $b[16], COD_SALIDA_ALMACEN = $b[17], COD_PERSONAL = $b[18],
                          COD_ESTADO_INGRESO_LIQUIDACION = $b[19], FECHA_LIQUIDACION = $b[20], COD_SALIDA_ALMACEN_DEVOLUCION = $b[21] where  codigo_hermes = $b[22] ";
        mssql_query($query);
    } else {
        $query = 'INSERT into INGRESOS_ALMACEN (cod_ingreso_almacen, cod_tipo_ingreso_almacen, cod_orden_compra, cod_gestion, cod_estado_ingreso_almacen, cod_devolucion,
                    FECHA_INGRESO_ALMACEN, COD_TIPO_DOCUMENTO, NRO_DOCUMENTO, FECHA_DOCUMENTO, CREDITO_FISCAL_SI_NO, OBS_INGRESO_ALMACEN, NRO_INGRESO_ALMACEN,
                    COD_PROVEEDOR, ESTADO_SISTEMA, COD_TIPO_COMPRA, COD_ALMACEN, COD_SALIDA_ALMACEN, COD_PERSONAL, COD_ESTADO_INGRESO_LIQUIDACION,
                    FECHA_LIQUIDACION, COD_SALIDA_ALMACEN_DEVOLUCION, codigo_hermes) VALUES (';
        foreach ($b as $c => $d) {
            $query .= $d . ",";
        }
        $query = substr($query, 0, -1);
        $query .= ");";
        mssql_query($query);
    }
}

/**/

$codigos_hermes = substr($codigos_hermes, 0, -1);

$cod_ingreso_almacen_baco = mssql_query(" select max(COD_INGRESO_ALMACEN) from INGRESOS_ALMACEN_DETALLE ");
$codigo_ingreso_detalle_baco = mssql_result($cod_ingreso_almacen_baco, 0, 0);
$codigo_ingreso_detalle_baco++;

$ingresos_detalle = "";
$sql_ingreso_detalle = mysql_query(" select * from ingreso_detalle_almacenes where cod_ingreso_almacen in ($codigos_hermes) ");
while ($row_ingreso_detalle = mysql_fetch_array($sql_ingreso_detalle)) {
    $cod_ingreso_almacen = $row_ingreso_detalle[0];
    $cod_material = $row_ingreso_detalle[1];
    $nro_lote = $row_ingreso_detalle[2];
    $fecha_vencimiento = $row_ingreso_detalle[3];
    $cantidad_unitaria = $row_ingreso_detalle[4];
    $cantidad_restante = $row_ingreso_detalle[5];
    $ingresos_detalle .= $cod_material . "," . $codigo_ingreso_detalle_baco . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . ",'" . 0 . "'," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $cod_ingreso_almacen . ",";
    $codigo_ingreso_detalle_baco++;
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
        $query_detalle = " UPDATE INGRESOS_ALMACEN_DETALLE SET COD_MATERIAL = $det[0], COD_SECCION = $det[2], NRO_UNIDADES_EMPAQUE = $det[3], CANT_TOTAL_INGRESO = $det[4], CANT_TOTAL_INGRESO_FISICO = $det[5],
                                         COD_UNIDAD_MEDIDA = $det[6], PRECIO_TOTAL_MATERIAL = $det[7], PRECIO_UNITARIO_MATERIAL = $det[8], COSTO_UNITARIO = $det[9], observacion = $det[10], PRECIO_NETO = $det[11], 
                                          COSTO_PROMEDIO = $det[12], COSTO_UNITARIO_ACTUALIZADO = $det[13], FECHA_ACTUALIZACION = $det[14], COSTO_UNITARIO_ACTUALIZADO_FINAL = $det[15] where cod_hermes = $det[16]  ";
        mssql_query($query_detalle);
    } else {
        $query_detalle = "INSERT into INGRESOS_ALMACEN_DETALLE (COD_MATERIAL,COD_INGRESO_ALMACEN,COD_SECCION,NRO_UNIDADES_EMPAQUE,CANT_TOTAL_INGRESO,CANT_TOTAL_INGRESO_FISICO,
                                        COD_UNIDAD_MEDIDA,PRECIO_TOTAL_MATERIAL,PRECIO_UNITARIO_MATERIAL,COSTO_UNITARIO,observacion,PRECIO_NETO,COSTO_PROMEDIO,COSTO_UNITARIO_ACTUALIZADO,
                                        FECHA_ACTUALIZACION,COSTO_UNITARIO_ACTUALIZADO_FINAL,cod_hermes) VALUES (";
        foreach ($det as $ingre => $detalle) {
            $query_detalle .= $detalle . ",";
        }
        $query_detalle = substr($query_detalle, 0, -1);
        $query_detalle .= ");";
        mssql_query($query_detalle);
    }
}

/* -----------------------------FIN INGRESOS---------------------------- */



/* -----------------------------SALIDAS------------------------------------- */

$sql_salida = mysql_query(" select s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, 
    s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino, s.cod_tiposalida, a.cod_almacen FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a where s.cod_tiposalida=ts.cod_tiposalida 
    and s.cod_almacen='1000' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida=2 and s.fecha < '$fecha_final' and s.fecha > '$fecha_inicio' order by s.nro_correlativo desc ");
$sql_codigo_baco = mssql_query(" select max(cod_salida_almacen) from salidas_almacen ");
$codigo_baco_salida = mssql_result($sql_codigo_baco, 0, 0);
$codigo_baco_salida++;

while ($row = mysql_fetch_array($sql_salida)) {
    $codigo_salida_hermes = $row[0];
    $fecha = $row[1];
    $hora = $row[2];
    $nombre = $row[3];
    $salida = $row[4];
    $nombre_almacen = $row[5];
    $observaciones = $row[6];
    $estado_salida = $row[7];
    $n_correlativo_s = $row[8];
    $salida_anulada = $row[9];
    $almacen_destino = $row[10];
    $codigo_tipo_salida = $row[11];
    $codigo_almacen = $row[12];
    $fecha = $fecha . " " . $hora . ".000";
    $output_salida .= $codigo_gestion . "," . $codigo_baco_salida . "," . 0 . "," . 0 . "," . 0 . "," . $codigo_tipo_salida . "," . $codigo_almacen . "," . 0 . ",'" . $fecha . "','" . $observaciones . "'," . $estado_salida . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . 0 . "," . $codigo_salida_hermes . ",";
    $codigo_baco_salida++;
    $codigos_salida_hermes .= $codigo_salida_hermes . ",";
}
$output_salida = substr($output_salida, 0, -1);
$output_salida = explode(",", $output_salida);
$output_salida = array_chunk($output_salida, 21);

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
        $query2 = " UPDATE SALIDAS_ALMACEN set  COD_GESTION = $bb[0], COD_ORDEN_PESADA = $bb[2] , COD_FORM_SALIDA = $bb[3], COD_PROD = $bb[4], COD_TIPO_SALIDA_ALMACEN = $bb[5], COD_AREA_EMPRESA = $bb[6],
                             NRO_SALIDA_ALMACEN = $bb[7], FECHA_SALIDA_ALMACEN = $bb[8], OBS_SALIDA_ALMACEN = $bb[9], ESTADO_SISTEMA = $bb[10], COD_ALMACEN = $bb[11], COD_ORDEN_COMPRA = $bb[12] , COD_PERSONAL = $bb[13],
                             COD_ESTADO_SALIDA_ALMACEN = $bb[14], COD_LOTE_PRODUCCION = $bb[15], COD_ESTADO_SALIDA_COSTO = $bb[16], cod_prod_ant = $bb[17], orden_trabajo = $bb[18], COD_PRESENTACION = $bb[19] where codigo_hermes = $bb[20]  ";
        mssql_query($query2);
    } else {
        $query2 = "insert into salidas_almacen (COD_GESTION,COD_SALIDA_ALMACEN,COD_ORDEN_PESADA,COD_FORM_SALIDA,COD_PROD,COD_TIPO_SALIDA_ALMACEN,COD_AREA_EMPRESA,NRO_SALIDA_ALMACEN,FECHA_SALIDA_ALMACEN,
                        OBS_SALIDA_ALMACEN, ESTADO_SISTEMA, COD_ALMACEN, COD_ORDEN_COMPRA, COD_PERSONAL, COD_ESTADO_SALIDA_ALMACEN, COD_LOTE_PRODUCCION, COD_ESTADO_SALIDA_COSTO, cod_prod_ant,
                        orden_trabajo, COD_PRESENTACION, codigo_hermes) VALUES (";
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

$cod_salida_almacen_baco = mssql_query(" select max(COD_SALIDA_ALMACEN) from SALIDAS_ALMACEN_DETALLE ");
$codigo_salida_detalle_baco = mssql_result($cod_salida_almacen_baco, 0, 0);
$codigo_salida_detalle_baco++;

$salidas_detalle = "";
$sql_salida_detalle = mysql_query(" select * from salida_detalle_almacenes where cod_salida_almacen in ($codigos_salida_hermes) ");

while ($row_salida_detalle = mysql_fetch_array($sql_salida_detalle)) {
    $cod_salida_almacen = $row_salida_detalle[0];
    $cod_material = $row_salida_detalle[1];
    $cantidad_unitaria = $row_salida_detalle[2];
    $observaciones = $row_salida_detalle[3];
    $salidas_detalle .= $codigo_salida_detalle_baco . "," . $cod_material . "," . 0 . "," . 0 . "," . 0 . "," . $cod_salida_almacen . ",";
    $codigo_salida_detalle_baco++;
}
$salidas_detalle = substr($salidas_detalle, 0, -1);
$salidas_detalle = explode(",", $salidas_detalle);
$salidas_detalle = array_chunk($salidas_detalle, 6);

$sql_veri_detalle_salida = mssql_query(" select cod_hermes from salidas_almacen_detalle where cod_hermes != null or cod_hermes != 0 ");
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
        $query_detalle = "UPDATE salidas_almacen_detalle set   COD_MATERIAL = $dett[1], CANTIDAD_SALIDA_ALMACEN  = $dett[2] ,COD_UNIDAD_MEDIDA  = $dett[3], 
        COD_ESTADO_MATERIAL  = $dett[4] where cod_hermes  = $dett[5] ";
        mssql_query($query_detalle);
    } else {
        $query_detalle = "INSERT into salidas_almacen_detalle (COD_SALIDA_ALMACEN,COD_MATERIAL,CANTIDAD_SALIDA_ALMACEN,COD_UNIDAD_MEDIDA,COD_ESTADO_MATERIAL,cod_hermes) VALUES (";
        foreach ($dett as $ingres => $detallee) {
            $query_detalle .= $detallee . ",";
        }
        $query_detalle = substr($query_detalle, 0, -1);
        $query_detalle .= ");";
        mssql_query($query_detalle);
    }
}

/* -----------------------------FIN SALIDAS-------------------------------- */
echo json_encode("Datos ingresados satisfactoriamente al sistema BACO");
?>

