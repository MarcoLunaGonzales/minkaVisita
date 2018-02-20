<?php

set_time_limit(0);
require_once ('../../conexion.inc');
$valor = $_REQUEST['valores'];

$valor_sub = substr($valor, 0,-1);
$separados = explode(",", $valor_sub);
$array_chunk = array_chunk($separados, 5);

$date = date('Y-m-d');

// print_r($array_chunk);

$sql = mysql_query("SELECT max(id) from banco_muestra_cantidad_visitador");
$id_A = mysql_result($sql,0,0);


if($id_A == null or $id_A =='' ){
    $id = 1;
}else{
    $id =  $id_A + 1;
}
foreach ($array_chunk as $valores_finales){
    $sql_inserta = mysql_query("UPDATE banco_muestra_cantidad_visitador set cantidad = $valores_finales[4] where id_for =$valores_finales[3] and cod_medico = $valores_finales[0] and cod_visitador = $valores_finales[1] and codigo_muestra = '$valores_finales[2]'");
    // echo("UPDATE banco_muestra_cantidad_visitador set cantidad = $valores_finales[4] where id_for =$valores_finales[3] and cod_medico = $valores_finales[0] and cod_visitador = $valores_finales[1] and codigo_muestra = '$valores_finales[2]'");

    $sql_update = mysql_query("UPDATE banco_muestras set estado = 1,fecha_aprobado = '$date' where id = $valores_finales[3]");
    $id++;
}
if($sql_inserta){
    $arr = array("estado" => "bien", "mensaje" => "Datos Aprobados Satisfactoriamente");
}else{
    $arr = array("estado" => "mal", "mensaje" => "No se Guardaron los datos, intentelo de nuevo");
}
echo json_encode($arr);
?>