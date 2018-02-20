<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_GET['cadena'];
$id = $_GET['id_cab'];
$cadena_sub = substr($cadena, 1);
$cadena_replace = str_replace(',@', '@', $cadena_sub);
$cadena_replace_sub = substr($cadena_replace, 0, -1);
$cadena_explode = explode("@", $cadena_replace_sub);
$cadena_espe = '';
$date = date('Y-m-d');
$count_de = 1;
$especialidad_anterior = '';

mysql_query("DELETE FROM plan_lineas where id_cab = $id");
// echo("DELETE FROM plan_lineas where id_cab = $id");
mysql_query("DELETE FROM plan_lineas_detalle where id_cab = $id");
// echo("DELETE FROM plan_lineas_detalle where id_cab = $id");

foreach ($cadena_explode as $regional) {
    $regional_explode = explode(",", $regional);
    $ciudad = $regional_explode[0];
    array_shift($regional_explode);
    foreach ($regional_explode as $especialidades) {
        $cadena_espe .= $especialidades . ",";
    }
    $cadena_espe2 = array_chunk($regional_explode, 2);
    $sql_id_i = mysql_query("select max(id) from plan_lineas");
    $max_id_i = mysql_result($sql_id_i, 0, 0);
//    echo $max_id;
    if ($max_id_i == '' or $max_id_i == NULL) {
        $id_i = 1;
    } else {
        $id_i = $max_id_i + 1;
    }

    $sql_inser_cab = mysql_query("INSERT into plan_lineas (id,ciudad,id_cab) values ($id_i,$ciudad,$id)");

    foreach ($cadena_espe2 as $especialidades_finales) {
        if($especialidad_anterior == $especialidades_finales[0]){
            $count_de = $count_de + 1;
        }else{
            $count_de = 1;            
        }
        $sql_inser_det = mysql_query("INSERT into plan_lineas_detalle (id,especialidad,linea,id_cab,de) values ($id_i,'$especialidades_finales[0]',$especialidades_finales[1],$id,$count_de)");
        // echo ("insert into plan_lineas_detalle (id,especialidad,linea) values ($id,'$especialidades_finales[0]',$especialidades_finales[1]");
        $especialidad_anterior = $especialidades_finales[0];
    }
    $id_i++;
}

echo json_encode("Datos Editados Satisfactoriamente")
?>