<?php

set_time_limit(0);
require("../../conexion.inc");

$codigo_medico = $_REQUEST['codigo_medico'];

$sql = mysql_query("Select cod_med from medicos where cod_med = $codigo_medico");

$num_sql = mysql_num_rows($sql);

if ($num_sql > 0) {

    $sql_delete_med = mysql_query("Delete from medicos where cod_med = $codigo_medico");
    $sql_delete_dir = mysql_query("Delete from direcciones_medicos where cod_med = $codigo_medico");
    $sql_delete_espe = mysql_query("Delete from especialidades_medicos where cod_med = $codigo_medico");
    $sql_delete_farmref = mysql_query("DELETE from farmacias_referencia_medico where cod_med =  $codigo_medico");
    $sql_delete_catmed = mysql_query("DELETE from categorizacion_medico where cod_med =  $codigo_medico");

    if ($sql_delete_med == 1 && $sql_delete_dir == 1 && $sql_delete_espe == 1 && $sql_delete_farmref == 1  && $sql_delete_catmed == 1) {
        
        $arr = array("mensaje" => true);
        echo json_encode($arr);
        
    } else {
        
        $arr = array("mensaje" => false);
        echo json_encode($arr);
        
    }
    
} else {
    
    $arr = array("mensaje" => false);
    echo json_encode($arr);
    
}
?>