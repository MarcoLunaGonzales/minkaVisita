<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
 */

require("conexion.inc");
require("estilos_regional_pri.inc");

$vector=explode(",", $datos);
$n=sizeof($vector);
for ($i=0;$i<$n;$i++) {
    $sql="delete from grupo_especial_detalle where codigo_grupo_especial=$cod_grupo and cod_med=$vector[$i]";
    $resp=mysql_query($sql);
}
echo "
<script type='text/javascript' language='javascript'>
    alert('Los datos fueron eliminados.');
    location.href='navegador_grupoespe_detalle.php?cod_grupo=$cod_grupo&codigo_linea=$codigo_linea&cod_ciudad=$cod_ciudad';
    //history.go(1);
</script>";

?>
