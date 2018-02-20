<?php
error_reporting(E_ALL);
set_time_limit(0);
require 'baco/coneccion.php';
$cont = 1;
$sql = mssql_query("select * from salidas_almacen_detalle where cod_salida_almacen > 61034 and cod_hermes > 71530");
while($row = mssql_fetch_array($sql)){
    $cod_salida_almacen = $row[0];
    $cod_material = $row[1];
    $cantidad_salida_almacen = $row[2];
    $cod_unidad_medida = $row[3];
    $cod_estado_material = $row[4];
    $cod_hermes = $row[5];
    $insert = mssql_query("Insert into salidas_almacen_detalle_ingreso (cod_salida_almacen,cod_material,cod_ingreso_almacen,etiqueta,costo_salida,cantidad,cod_hermes) 
        values($cod_salida_almacen,$cod_material,0,0,0,$cantidad_salida_almacen,$cod_hermes)");
//    echo("Insert into salidas_almacen_detalle_ingreso (cod_salida_almacen,cod_material,cod_ingreso_almacen,etiqueta,costo_salida,cantidad,cod_hermes) 
//        values($cod_salida_almacen,$cod_material,0,0,0,$cantidad_salida_almacen,$cod_hermes)")."<br />";
    if($insert){
        echo "OK <br />";
    }else{
        echo("Insert into salidas_almacen_detalle_ingreso (cod_salida_almacen,cod_material,cod_ingreso_almacen,etiqueta,costo_salida,cantidad,cod_hermes) 
        values($cod_salida_almacen,$cod_material,0,0,0,$cantidad_salida_almacen,$cod_hermes)")."<br />";
    }
}

?>

