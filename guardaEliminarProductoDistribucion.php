<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2005
 */
require("conexion.inc");
$ciclo_trabajo = $_POST['codCicloDist'];
$codigo_linea = $_POST['codLineaDist'];
$codigo_gestion = $_POST['codGestionDist'];
$codMuestra = $_POST['codProdOrigen'];

$sqlParrilla = "select distinct(p.codigo_parrilla) from parrilla p, parrilla_detalle pd where 
				 p.codigo_linea=$codigo_linea and codigo_gestion=$codigo_gestion and cod_ciclo=$ciclo_trabajo 
				 and pd.codigo_muestra='$codMuestra' and p.codigo_parrilla=pd.codigo_parrilla";
$respParrilla = mysql_query($sqlParrilla);
while ($datParrilla = mysql_fetch_array($respParrilla)) {
    $codParrilla = $datParrilla[0];
    //echo $codParrilla." ";
    $sqlDel = "delete from parrilla_detalle where codigo_parrilla='$codParrilla' and codigo_muestra='$codMuestra'";
    $respDel = mysql_query($sqlDel);
    //reordenamos las prioridades
    $sqlDetalle = "select codigo_muestra from parrilla_detalle where codigo_parrilla='$codParrilla' order by prioridad";
    $respDetalle = mysql_query($sqlDetalle);
    $i = 1;
    while ($datDetalle = mysql_fetch_array($respDetalle)) {
        $codigoMuestra = $datDetalle[0];
        $sqlUpd = "update parrilla_detalle set prioridad=$i where codigo_parrilla='$codParrilla' and codigo_muestra='$codigoMuestra'";
        $respUpd = mysql_query($sqlUpd);
        $i++;
    }
}
$sqlDelDist = "delete from distribucion_productos_visitadores where cod_ciclo=$ciclo_trabajo and codigo_gestion=$codigo_gestion and codigo_linea=$codigo_linea
		and codigo_producto='$codProdOrigen'";
$respDelDist = mysql_query($sqlDelDist);

echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			window.close();
			</script>";
?>