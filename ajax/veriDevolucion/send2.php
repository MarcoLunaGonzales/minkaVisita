<?php

set_time_limit(0);
require_once ('../../conexion.inc');

$valor = $_REQUEST['valores'];
$separados = explode("|", $valor);

$cod_ciclos = $separados[0];
$cod_gestiones = $separados[1];

if ($global_usuario == 1100) {
    $sqlVis = "select d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, 
				funcionarios f where d.codigo_ciclo=$cod_ciclos and 
				d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario 
				and f.cod_ciudad in (122,116,124)   and d.estado_devolucion=1 and d.tipo_devolucion in (1,2)";
} else {
    $sqlVis = "select d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, 
				funcionarios f where d.codigo_ciclo=$cod_ciclos and 
				d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario 
				and f.cod_ciudad='$global_agencia' and d.estado_devolucion=1 and d.tipo_devolucion in (1,2)";
}

$respVis = mysql_query($sqlVis);
$res = mysql_num_rows($respVis);
if ($res >= 1) {
    $arr = array("mensaje" => "bad", "valores" => $valor);
    echo json_encode($arr);
} else {
    $arr = array("mensaje" => "good", "valores" => $valor);
    echo json_encode($arr);
}
?>