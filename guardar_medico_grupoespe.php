<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
 */

require("conexion.inc");
require("estilos_gerencia.inc");

$cod_grupo    = $_GET["cod_grupo"];
$codigo_linea = $_GET["codigo_linea"];
$cod_ciudad   = $_GET["cod_ciudad"];

$vecDat = explode(",", $vecdatos); //codigos
//$vecLv = explode(",", $veclinvis); //codigos linea visita
$n1 = sizeof($vecDat);
//$n2 = sizeof($vecLv);
//if($n1==$n2) {
if(true) {
    for($i=0;$i<$n1;$i++) {
        //$sql="INSERT INTO grupo_especial_detalle VALUES($cod_grupo,$vecDat[$i],$vecLv[$i]) ";
        $sql="INSERT INTO grupo_especial_detalle VALUES($cod_grupo,$vecDat[$i]) ";
        $resp=mysql_query($sql);
        //echo "<br>$vecDat[$i],$vecLv[$i]";
    }
    echo "<script type='text/javascript' language='javascript'>
           alert('Los datos fueron se insertaron correctamente.');
           location.href='navegador_grupoespe_detalle.php?cod_grupo=$cod_grupo';
           //history.go(-2);
           </script>";
} else {
    echo "<script type='text/javascript' language='javascript'>
           alert('No coincide el numero de parametros.');
           //history.go(-2);
           </script>";
}

?>
