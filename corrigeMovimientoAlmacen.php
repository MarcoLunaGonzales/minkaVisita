<?php
require('conexion.inc');
$codigo_almacenarreglar=$codigo_almacen;

$codigo_almacenarreglar=1000; //codigo del almacen a corregir, tabla: almacenes

$cadCodigoMaterial="23091,M133,CFCO0123,CFCO0121";

$vecCodigoMaterial=explode(",", $cadCodigoMaterial);
$total=count($vecCodigoMaterial);
echo "<br>";
echo "TOTAL ELEMENTOS A CORREGIR: $total<br>";
//
for($i=0;$i<$total;$i++) {

    $codigo_material=$vecCodigoMaterial[$i];

    $sql_det_ingreso="SELECT id.cod_ingreso_almacen, id.cod_material, id.nro_lote, id.cantidad_unitaria FROM ingreso_detalle_almacenes id, ingreso_almacenes i WHERE i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.cod_almacen='$codigo_almacenarreglar' AND i.grupo_ingreso='1'AND id.cod_material='$codigo_material'ORDER BY cod_ingreso_almacen";
    $resp_det_ingreso=mysql_query($sql_det_ingreso);
    while($dat_det_ingreso=mysql_fetch_array($resp_det_ingreso)) {   
        $cod_ingreso=$dat_det_ingreso[0];
        $cod_material=$dat_det_ingreso[1];
        $nro_lote=$dat_det_ingreso[2];
        $cant_unit=$dat_det_ingreso[3];
        $sql_actualiza_cant=mysql_query("UPDATE ingreso_detalle_almacenes SET cantidad_restante='$cant_unit' WHERE cod_ingreso_almacen='$cod_ingreso' AND cod_material='$cod_material' AND nro_lote='$nro_lote'");
    }

    $sql_salida_detalle="SELECT sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria, s.grupo_salida FROM salida_detalle_almacenes sd, salida_almacenes s WHERE s.cod_salida_almacenes=sd.cod_salida_almacen AND s.cod_almacen='$codigo_almacenarreglar' AND s.grupo_salida=1 AND sd.cod_material='$codigo_material'";
    $resp_salida_detalle=mysql_query($sql_salida_detalle);
    while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle)) {   
        $cod_salida_almacen=$dat_salida_detalle[0];
        $sql_del_salidadetalleingreso="DELETE FROM salida_detalle_ingreso WHERE cod_salida_almacen='$cod_salida_almacen'";
        $resp_del_salidadetalleingreso=mysql_query($sql_del_salidadetalleingreso);
    }
    
    $sql_salida_detalle="SELECT sd.cod_salida_almacen, sd.cod_material, sd.cantidad_unitaria, s.grupo_salida FROM salida_detalle_almacenes sd, salida_almacenes s WHERE s.cod_salida_almacenes=sd.cod_salida_almacen AND s.cod_almacen='$codigo_almacenarreglar' AND s.grupo_salida=1 AND s.salida_anulada=0 and sd.cod_material='$codigo_material'";
    $resp_salida_detalle=mysql_query($sql_salida_detalle);
    while($dat_salida_detalle=mysql_fetch_array($resp_salida_detalle)) {   
        $cod_salida_almacen=$dat_salida_detalle[0];
        $cod_material=$dat_salida_detalle[1];
        $cant_unit_salida=$dat_salida_detalle[2];
        $grupo_salida=$dat_salida_detalle[3];

        $sql_detalle_ingreso="SELECT id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote FROM ingreso_detalle_almacenes id, ingreso_almacenes i WHERE i.cod_ingreso_almacen=id.cod_ingreso_almacen AND i.cod_almacen='$codigo_almacenarreglar'AND id.cod_material='$cod_material' AND id.cantidad_restante<>0 AND i.ingreso_anulado=0 ORDER BY id.fecha_vencimiento";
        $resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
        $cantidad_bandera=$cant_unit_salida;
        $bandera=0;
        while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso)) {   
            $cod_ingreso_almacen=$dat_detalle_ingreso[0];
            $cantidad_restante=$dat_detalle_ingreso[1];
            $nro_lote=$dat_detalle_ingreso[2];
            if($bandera!=1) {   
                if($cantidad_bandera>$cantidad_restante) {   
                    $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso VALUES('$cod_salida_almacen','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','1')";
                    $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                    $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
                    $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=0 WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND cod_material='$cod_material' AND nro_lote='$nro_lote'";
                    $resp_upd_cantidades = mysql_query($upd_cantidades);
                } else {   
                    $sql_salida_det_ingreso="INSERT INTO salida_detalle_ingreso VALUES('$cod_salida_almacen','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','1')";
                    $resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
                    $cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
                    $bandera=1;
                    $upd_cantidades="UPDATE ingreso_detalle_almacenes SET cantidad_restante=$cantidad_a_actualizar WHERE cod_ingreso_almacen='$cod_ingreso_almacen' AND cod_material='$cod_material' AND nro_lote='$nro_lote'";
                    $resp_upd_cantidades=mysql_query($upd_cantidades);
                    $cantidad_bandera=$cantidad_bandera-$cantidad_restante;
                }
            }
        }
    }
    echo "Muestra $codigo_material, corregido.<br>";
}
echo "OK MUESTRAS.....";
