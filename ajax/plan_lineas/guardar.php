<?php

set_time_limit(0);
require("../../conexion.inc");

$cadena = $_GET['cadena'];
$cadena_sub = substr($cadena, 1);
$cadena_replace = str_replace(',@', '@', $cadena_sub);
$cadena_replace_sub = substr($cadena_replace, 0, -1);
$cadena_explode = explode("@", $cadena_replace_sub);
$cadena_espe = '';
$date = date('Y-m-d');
$count_de = 1;
$especialidad_anterior = '';

$sql_id = mysql_query("select max(id) from plan_linea_cab");
$max_id = mysql_result($sql_id, 0, 0);
//    echo $max_id;
if ($max_id == '' or $max_id == NULL) {
    $id = 1;
} else {
    $id = $max_id + 1;
}
$nombre = "Plan de Lineas " . $id;
//$sql_update = mysql_query("update plan_linea_cab set estado = 0 ");
$sql_insert_p_cab = mysql_query("INSERT into plan_linea_cab (id,nombre,fecha,estado) values($id,'$nombre','$date',0)");

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
        // echo $especialidad_anterior . " = " .$especialidades_finales[0] . " <br /> ";
        if($especialidad_anterior == $especialidades_finales[0]){
            $count_de = $count_de + 1;
        }else{
            $count_de = 1;            
        }
        $sql_inser_det = mysql_query("INSERT into plan_lineas_detalle (id,especialidad,linea,id_cab,de) values ($id_i,'$especialidades_finales[0]',$especialidades_finales[1],$id,$count_de)");
        // echo("INSERT into plan_lineas_detalle (id,especialidad,linea,id_cab,de) values ($id_i,'$especialidades_finales[0]',$especialidades_finales[1],$id,$count_de);");
        $especialidad_anterior = $especialidades_finales[0];
    }
    $id_i++;
}

echo json_encode("Datos ingresados Satisfactoriamente")
?>