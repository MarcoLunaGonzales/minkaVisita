<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
 */
require("conexion.inc");
require('estilos_gerencia.inc');
$vector = explode(",", $datos);
$n = sizeof($vector);
for ($i = 0; $i < $n; $i++) {
    $sql = "delete from lineas_visita_visitadores 
		where codigo_l_visita=$cod_linea_vis and codigo_funcionario='$vector[$i]' and codigo_ciclo = $ciclo and codigo_gestion = $gestion";
//    echo $sql;
    $resp = mysql_query($sql);
}
echo "<script language='Javascript'>
            alert('Los datos fueron eliminados.');
            location.href='navegador_lineasvisitafuncionario.php?cod_linea_vis=$cod_linea_vis&ciclo=$ciclo&gestion=$gestion';
        </script>";
?>