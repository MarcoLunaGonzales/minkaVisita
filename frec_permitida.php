<?php

set_time_limit(0);
require 'conexion.inc';
$medicos_s_c = mysql_query("select * from categorias_lineas where cod_med in (SELECT cod_med from medicos where cod_ciudad = 116 )");

$count = 1;
while ($row_medicos = mysql_fetch_array($medicos_s_c)) {
    $codigo_liena = $row_medicos[0];
    $codigo_medico = $row_medicos[1];
    $categoria = $row_medicos[3];
    $frecuencia_permitida = $row_medicos[5];
    $update2 = (" UPDATE categorias_lineas set frecuencia_linea = $frecuencia_permitida where cod_med = $codigo_medico and codigo_linea = $codigo_liena and categoria_med = '$categoria' ");
    $update = mysql_query(" UPDATE categorias_lineas set frecuencia_linea = $frecuencia_permitida where cod_med = $codigo_medico and codigo_linea = $codigo_liena and categoria_med = '$categoria' ");
    if($update){
        echo $count ." " . $codigo_medico . " " .  $update2 . " <span style='color:blue'> Actualizado</span><br />" ;
    }else{
        echo $count ." " . $codigo_medico . " " .  $update2 . " <span style='color:red'> Error</span><br />";
    }
    $count++;
}

echo "Termino de ejecutarse";
?>
