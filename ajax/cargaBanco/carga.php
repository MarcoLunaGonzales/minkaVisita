<?php

set_time_limit(0);
require_once ('../../conexion.inc');
$valor = $_REQUEST['valores'];

$valor_sub = substr($valor, 0,-1);
$separados = explode(",", $valor_sub);
$array_chunk = array_chunk($separados, 6);

$date = date('Y-m-d');


$sql = mysql_query("SELECT max(id) from banco_muestra_cantidad_visitador");
$id_A = mysql_result($sql,0,0);


if($id_A == null or $id_A =='' ){
    $id = 1;
}else{
    $id =  $id_A + 1;
}
foreach ($array_chunk as $valores_finales){
    $sql_inserta = mysql_query("INSERT into banco_muestra_cantidad_visitador (id,cod_medico,cod_visitador,cantidad,codigo_muestra,id_for) values($id,$valores_finales[0],$valores_finales[1],$valores_finales[5],'$valores_finales[2]','$valores_finales[3]')");
    $sql_update = mysql_query("UPDATE banco_muestras set estado = 3,fecha_pre_aprobado = '$date' where id = $valores_finales[3]");
    $id++;
}
if($sql_inserta){
    $arr = array("estado" => "bien", "mensaje" => "Datos Aprobados Satisfactoriamente");
}else{
    $arr = array("estado" => "mal", "mensaje" => "No se Guardaron los datos, intentelo de nuevo");
}
echo json_encode($arr);
?>