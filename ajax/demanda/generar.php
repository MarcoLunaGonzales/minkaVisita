<?php

set_time_limit(0);
require("../../conexion.inc");

$nombre = $_POST['nombre'];
$existencia_a_la_fecha = $_POST['fecha'];
$existencia_a_la_fecha_explode = explode("/", $existencia_a_la_fecha);
$existencia_a_la_fecha_final = $existencia_a_la_fecha_explode[2] . "-" . $existencia_a_la_fecha_explode[0] . "-" . $existencia_a_la_fecha_explode[1];
$fecha_generacion = date('Y-m-d');

$max_codigo_sql = mysql_query("select max(id) from demanda_mm");
$max_codigo = mysql_result($max_codigo_sql, 0, 0);
//echo $max_codigo;
if ($max_codigo == 0 or $max_codigo == '' or $max_codigo == NULL) {
    $id = 1;
} else {
    $id = $max_codigo + 1;
}
//$id_detalle = $id;
//$sql_detalle_cab = mysql_query("insert into demanda_mm (id,nombre,fecha_generacion,existencias_a_la_fecha) values ($id,'$nombre','$fecha_generacion','$existencia_a_la_fecha_final')");

$sql_item = mysql_query("select codigo, descripcion, presentacion, codigo_anterior, stock_minimo, stock_reposicion, stock_maximo from muestras_medicas where estado=1 order by descripcion, presentacion");
while ($datos_item = mysql_fetch_array($sql_item)) {
    $codigo_item = $datos_item[0];
    $codigo_anterior = $datos_item[3];
    $stockMinimo = $datos_item[4];
    $stockReposicion = $datos_item[5];
    $stockMaximo = $datos_item[6];
    $nombre_item = "$datos_item[1] $datos_item[2]";
    $sql_nombre_linea = "select l.nombre_linea from muestras_medicas m, lineas l
				where m.codigo_linea=l.codigo_linea and m.codigo='$codigo_item'";
    $resp_nombre_linea = mysql_query($sql_nombre_linea);
    $dat_nombre_linea = mysql_fetch_array($resp_nombre_linea);
    $nombre_linea = $dat_nombre_linea[0];
    $sql_ingresos = "select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.fecha<='$existencia_a_la_fecha_final' and i.cod_almacen='1000'
			and id.cod_material='$codigo_item' and i.ingreso_anulado=0 and i.grupo_ingreso='1'";
    $resp_ingresos = mysql_query($sql_ingresos);
    $dat_ingresos = mysql_fetch_array($resp_ingresos);
    $cant_ingresos = $dat_ingresos[0];
    $sql_salidas = "select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.fecha<='$existencia_a_la_fecha_final' and s.cod_almacen='1000'
			and sd.cod_material='$codigo_item' and s.salida_anulada=0 and s.grupo_salida='1'";
    $resp_salidas = mysql_query($sql_salidas);
    $dat_salidas = mysql_fetch_array($resp_salidas);
    $cant_salidas = $dat_salidas[0];
    $stock2 = $cant_ingresos - $cant_salidas;

    $sql_stock = "select SUM(id.cantidad_restante) from ingreso_detalle_almacenes id, ingreso_almacenes i
			where id.cod_material='$codigo_item' and i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.ingreso_anulado=0 and i.cod_almacen='1000'";
    $resp_stock = mysql_query($sql_stock);
    $dat_stock = mysql_fetch_array($resp_stock);
    $stock_real = $dat_stock[0];
    $diferenciaStock = $stock2 - $stockReposicion;
    // echo $diferenciaStock;
    // if ($diferenciaStock < 0){
    //     $diferenciaStock = $diferenciaStock * (-1);
    // }
    $demanda = $stockMaximo - $stock2;

    // if ($stock2 >= 0 and $diferenciaStock < 0) {
    // if ($stock2 > 0 or $diferenciaStock < 0 ) {
        $sql1 = mysql_query("insert into demanda_mm (id,nombre,fecha_generacion,existencias_a_la_fecha) values ($id,'$nombre','$fecha_generacion','$existencia_a_la_fecha_final')");
        $sql2 = mysql_query("insert into demanda_mm_detalle values($id,'$codigo_item',$stock2,$stockMinimo,$stockReposicion,$stockMaximo,$demanda)");
    // }
}
//if ($sql1) {
    $return['error'] = false;
    $return['msg'] = 'Datos Ingresados Satisfactoriamente';
//} else {
//    $return['error'] = true;
//    $return['msg'] = 'No hubo datos que ingresar o surgio un problema. Intentelo denuevo';
//}
echo json_encode($return);
?>