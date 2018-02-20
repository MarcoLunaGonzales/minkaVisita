<?php

set_time_limit(0);
require_once ('../../conexion.inc');
$valor = $_REQUEST['valores'];
$separados = explode("|", $valor);


$cod_ciclos = $separados[0];
$cod_gestiones = $separados[1];
$rpt_fecha = date('Y-m-d');
$flag = 1;

$almacen = mysql_query("SELECT cod_almacen FROM almacenes where cod_ciudad = $global_agencia");
$almacen_final = mysql_result($almacen, 0,0);


/* MUESTRAS MEDICAS */


if ($global_usuario == 1100) {
    $sqlVis = "SELECT d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, funcionarios f where d.codigo_ciclo=$cod_ciclos and d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario and f.cod_ciudad in (122,116,124) and d.estado_devolucion=2 and d.tipo_devolucion = 2";
} else {
    $sqlVis = "SELECT d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion FROM devoluciones_ciclo d, funcionarios f WHERE d.codigo_ciclo=$cod_ciclos and d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario and f.cod_ciudad='$global_agencia' and d.estado_devolucion=2 and d.tipo_devolucion = 2";
}
$respVis = mysql_query($sqlVis);

while ($row_datos = mysql_fetch_array($respVis)) {
    $codigos .= $row_datos[0] . ",";
}

$codigos_finales = substr($codigos, 0, -1);



$sql_detalle_codigos_materiales = mysql_query("SELECT codigo_material, SUM(cantidad_ing_almacen) FROM devoluciones_ciclodetalle where codigo_devolucion in ($codigos_finales) and cantidad_ing_almacen > 0  GROUP BY 1 ORDER BY 1");

while ($row_codigos = mysql_fetch_array($sql_detalle_codigos_materiales)) {
    $codigos_finales_materiales .= $row_codigos[0] . "," . $row_codigos[1] . ",";
}
$cod_fin_mat = substr($codigos_finales_materiales, 0, -1);
$cod_fin_mat2 = explode(",", $cod_fin_mat);
$codigos_finales_verificacion_stock = array_chunk($cod_fin_mat2, 2);

//print_r($codigos_finales_verificacion_stock);

foreach ($codigos_finales_verificacion_stock as $a => $b) {
    $sql_ingresos = "SELECT sum(id.cantidad_unitaria) FROM ingreso_almacenes i, ingreso_detalle_almacenes id
    where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$rpt_fecha' and i.cod_almacen='$almacen_final'
    and id.cod_material='$b[0]' and i.ingreso_anulado=0 and i.grupo_ingreso='2'";
    $resp_ingresos = mysql_query($sql_ingresos);
    $dat_ingresos = mysql_fetch_array($resp_ingresos);
    $cant_ingresos = $dat_ingresos[0];
    $sql_salidas = "SELECT sum(sd.cantidad_unitaria) FROM salida_almacenes s, salida_detalle_almacenes sd
    where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$rpt_fecha' and s.cod_almacen='$almacen_final'
    and sd.cod_material='$b[0]' and s.salida_anulada=0 and s.grupo_salida='2'";
    $resp_salidas = mysql_query($sql_salidas);
    $dat_salidas = mysql_fetch_array($resp_salidas);
    $cant_salidas = $dat_salidas[0];
    $stock2 = $cant_ingresos - $cant_salidas;
    if ($b[1] > $stock2) {
        $flag = $flag * 0;
    } else {
        $flag = $flag * 1;
    }
}


//borrar
$flag=1;

if ($flag == 0) {
    $arr = array("estado" => "mal", "mensaje" => "Las cantidades sobrepasan el stock actual");
    echo json_encode($arr);
} else {

    $sql_detalle_devolucion = mysql_query("SELECT codigo_material, SUM(cantidad_ing_almacen) FROM devoluciones_ciclodetalle where codigo_devolucion in ($codigos_finales) and cantidad_ing_almacen > 0 GROUP BY 1 ORDER BY 1");

    while ($row_materiales_devueltos = mysql_fetch_array($sql_detalle_devolucion)) {
        $codigo_material_nombre_material_devueltos .= $row_materiales_devueltos[0] . "," . $row_materiales_devueltos[1] . ",";
    }

    $codigos_materiales_nombre_material_devueltos1 = substr($codigo_material_nombre_material_devueltos, 0, -1);
    $codigos_materiales_nombre_material_devueltos2 = explode(",", $codigos_materiales_nombre_material_devueltos1);
    $salidas_finales_codigos_nombres = array_chunk($codigos_materiales_nombre_material_devueltos2, 2);

    $codigo_almacen = mysql_query("SELECT cod_almacen FROM almacenes where cod_ciudad = $global_agencia");
    $codigo_almacen = mysql_result($codigo_almacen, 0, 0);


    $global_almacen = $codigo_almacen;
    $tipo_salida = 1005;
    $fecha_real = date("Y-m-d");
    $hora_salida = date("H:i:s");
    $sql_territorio = mysql_query("SELECT cod_ciudad FROM almacenes where cod_almacen = $global_almacen");
    $territorio = mysql_result($sql_territorio, 0,0);
    $almacen = 1000;
    $observaciones = "Devolucion de material de apoyo Ciclo: $cod_ciclos | Gestion: $cod_gestiones a almacen central";
    $sql_gestion = "SELECT nombre_gestion FROM gestiones where codigo_gestion='$cod_gestiones'";
    $resp_gestion = mysql_query($sql_gestion);
    $dat_gestion = mysql_fetch_array($resp_gestion);
    $nombre_gestion = $dat_gestion[0];
    $grupo_salida = 2;

    $sql = "SELECT cod_salida_almacenes FROM salida_almacenes order by cod_salida_almacenes desc";
    $resp = mysql_query($sql);
    $dat = mysql_fetch_array($resp);
    $num_filas = mysql_num_rows($resp);
    if ($num_filas == 0) {
        $codigo = 1;
    } else {
        $codigo = $dat[0];
        $codigo++;
    }
    $sql2 = "SELECT max(nro_correlativo) FROM salida_almacenes where cod_almacen='$almacen' and grupo_salida='$grupo_salida' order by cod_salida_almacenes desc";
    $resp2 = mysql_query($sql2);
    $nro_correlativo = mysql_result($resp2, 0, 0);
    $nro_correlativo++;

    $sql_inserta_salida_almacen = mysql_query("INSERT into salida_almacenes values('$codigo','$global_almacen','$tipo_salida','$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',1,'$grupo_salida','','',0,0,0,0,'','$nro_correlativo',0)");

    foreach ($salidas_finales_codigos_nombres as $entrada => $valores) {
        $cod_material = $valores[0];
        $cantidad = $valores[1];
        $sql_detalle_ingreso = "SELECT id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote FROM ingreso_detalle_almacenes id, ingreso_almacenes i where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and id.cod_material='$cod_material'and id.cantidad_restante> 0 and i.`ingreso_anulado`=0 order by id.fecha_vencimiento";
        $resp_detalle_ingreso = mysql_query($sql_detalle_ingreso);
        $cantidad_bandera = $cantidad;
        $bandera = 0;
        while ($dat_detalle_ingreso = mysql_fetch_array($resp_detalle_ingreso)) {
            $cod_ingreso_almacen = $dat_detalle_ingreso[0];
            $cantidad_restante = $dat_detalle_ingreso[1];
            $nro_lote = $dat_detalle_ingreso[2];
            if ($bandera != 1) {
                if ($cantidad_bandera > $cantidad_restante) {
                    $sql_salida_det_ingreso = "INSERT into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupo_salida')";
                    $resp_salida_det_ingreso = mysql_query($sql_salida_det_ingreso);
                    $cantidad_bandera = $cantidad_bandera - $cantidad_restante;
                    $upd_cantidades = "UPDATE ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
                    $resp_upd_cantidades = mysql_query($upd_cantidades);
                } else {
                    $sql_salida_det_ingreso = "INSERT into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_salida')";
                    $resp_salida_det_ingreso = mysql_query($sql_salida_det_ingreso);
                    $cantidad_a_actualizar = $cantidad_restante - $cantidad_bandera;
                    $bandera = 1;
                    $upd_cantidades = "UPDATE ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
                    $resp_upd_cantidades = mysql_query($upd_cantidades);
                    $cantidad_bandera = $cantidad_bandera - $cantidad_restante;
                }
            }
        }
        $sql_inserta2 = mysql_query("INSERT into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
//        fin prueba salida almacenes
    }
    $arr = array("estado" => "bien", "mensaje" => "Guardado satisfactoriamente en almacenes");
    echo json_encode($arr);
}
?>