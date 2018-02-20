<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2005
 */
//error_reporting(0);
require("conexion.inc");
$ciclo_trabajo = $_POST['codCicloDist'];
$codigo_gestion = $_POST['codGestionDist'];
$codMuestra = $_POST['codProdOrigen'];

//$sql_validacion = mysql_query(" SELECT cantidad_distribuida from distribucion_grupos_especiales where codigo_gestion = $codigo_gestion and cod_ciclo = $ciclo_trabajo and codigo_producto = '$codMuestra' ");
//while ($row_verificacion = mysql_fetch_array($sql_validacion)) {
//    if ($row_verificacion == 0) {
        $sqlParrilla = "select distinct(p.codigo_parrilla_especial) from parrilla_especial p, parrilla_detalle_especial pd where 
				p.codigo_gestion=$codigo_gestion and p.cod_ciclo=$ciclo_trabajo and p.codigo_linea = 1021
				 and pd.codigo_muestra='$codMuestra' and p.codigo_parrilla_especial=pd.codigo_parrilla_especial";
        $respParrilla = mysql_query($sqlParrilla);
        while ($datParrilla = mysql_fetch_array($respParrilla)) {
            $codParrilla = $datParrilla[0];

            $sqlDel = "delete from parrilla_detalle_especial where codigo_parrilla_especial='$codParrilla' and codigo_muestra='$codMuestra'";
            $respDel = mysql_query($sqlDel);

            //borramos de la distribucion de grupos especiales
            //reordenamos las prioridades
            $sqlDetalle = "select codigo_muestra from parrilla_detalle_especial where codigo_parrilla_especial='$codParrilla' order by prioridad";
            $respDetalle = mysql_query($sqlDetalle);
            $i = 1;

            while ($datDetalle = mysql_fetch_array($respDetalle)) {
                $codigoMuestra = $datDetalle[0];
                $sqlUpd = "update parrilla_detalle_especial set prioridad=$i where codigo_parrilla_especial='$codParrilla' and codigo_muestra='$codigoMuestra'";
                $respUpd = mysql_query($sqlUpd);
                $i++;
            }
        }
        $sql_borrar_distribucion_grupos_especiales = mysql_query(" delete from distribucion_grupos_especiales where cod_ciclo = $ciclo_trabajo and codigo_gestion = $codigo_gestion and codigo_producto = '$codMuestra' ");
        $sqlDelDist = "delete from distribucion_productos_visitadores where cod_ciclo=$ciclo_trabajo and codigo_gestion=$codigo_gestion and codigo_linea=1021
		and codigo_producto='$codProdOrigen'";
        $respDelDist = mysql_query($sqlDelDist);
        echo "<script language='Javascript'>
                    alert('Los datos fueron eliminados.');
                    window.close();
                    </script>";
//    } else {
//        echo "<script language='Javascript'>
//                    alert('Los datos No fueron eliminados porque ya se hizo la distribicion del producto.');
//                    window.close();
//                    </script>";
//    }
//}
?>