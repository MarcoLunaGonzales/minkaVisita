<?php 
include ('coneccion.php');
$count = 1;
$items = mssql_query("select COD_MATERIAL,COD_INGRESO_ALMACEN, CANT_TOTAL_INGRESO, FECHA_ACTUALIZACION,cod_hermes from INGRESOS_ALMACEN_DETALLE where cod_hermes in (45536,45534,45531,45520) ");
while($row =  mssql_fetch_array($items)){
    $cod_material = $row[0];
    $cod_ingreso_almacen = $row[1];
    $cant_total_ingreso = $row[2];
    $fecha_actualizacion = $row[3];
    $cod_hermes = $row[4];
    $inster = mssql_query("insert into INGRESOS_ALMACEN_DETALLE_ESTADO (ETIQUETA, COD_INGRESO_ALMACEN, COD_MATERIAL, COD_ESTADO_MATERIAL, 
        COD_EMPAQUE_SECUNDARIO_EXTERNO, CANTIDAD_PARCIAL, CANTIDAD_RESTANTE, FECHA_VENCIMIENTO, LOTE_MATERIAL_PROVEEDOR, 
        LOTE_INTERNO, FECHA_MANUFACTURA, FECHA_REANALISIS, OBSERVACIONES, OBS_CONTROL_CALIDAD, fecha_vencimiento1, fecha_reanalisis1, 
        fecha_vencimiento2, fecha_reanalisis2, cod_hermes) values(1,$cod_ingreso_almacen,$cod_material,2,1,$cant_total_ingreso,$cant_total_ingreso,'','','','','','','','','','','',$cod_hermes)");
    echo $count;
    if($inster){echo " ok ";}else{echo " <span style='color:red'>bad</span> ";}
    echo("insert into INGRESOS_ALMACEN_DETALLE_ESTADO (ETIQUETA, COD_INGRESO_ALMACEN, COD_MATERIAL, COD_ESTADO_MATERIAL, 
        COD_EMPAQUE_SECUNDARIO_EXTERNO, CANTIDAD_PARCIAL, CANTIDAD_RESTANTE, FECHA_VENCIMIENTO, LOTE_MATERIAL_PROVEEDOR, 
        LOTE_INTERNO, FECHA_MANUFACTURA, FECHA_REANALISIS, OBSERVACIONES, OBS_CONTROL_CALIDAD, fecha_vencimiento1, fecha_reanalisis1, 
        fecha_vencimiento2, fecha_reanalisis2, cod_hermes) values(1,$cod_ingreso_almacen,$cod_material,2,1,$cant_total_ingreso,$cant_total_ingreso,'','','','','','','','','','','',$cod_hermes)");
    echo "<br />";
    echo "<br />";
    $count++;
}
?>