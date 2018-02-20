<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$funcionarios = $_GET['funcionarios'];
$ciclo = $_GET['ciclo'];
$linea = $_GET['linea'];

$ciclos = explode("-", $ciclo);
$ciclos_finales = $ciclos[0];
$gestion = $ciclos[1];
foreach ($funcionarios as $funcionario) {
    $funcionarios_finales .= $funcionario . ",";
}
$funcionarios_finales_sub = substr($funcionarios_finales, 0, -1);
$txtsql='SELECT DISTINCT rc.cod_rutero, rc.nombre_rutero from rutero_maestro_cab rc, rutero_maestro rm, rutero_maestro_detalle rd where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.estado_aprobado = 2 and rc.codigo_linea = "' . $linea . '" and rc.codigo_ciclo = "' . $ciclos_finales . '" and rc.codigo_gestion = "' . $gestion . '" and rm.cod_visitador in (' . $funcionarios_finales_sub . ')';
//echo $txtsql;
$results = mysql_query($txtsql);
while (is_resource($results) && $row = mysql_fetch_object($results)) {
    $response .= "<option value='$row->cod_rutero'>" .  $row->nombre_rutero . "</option>";
}
echo json_encode($response);
?>