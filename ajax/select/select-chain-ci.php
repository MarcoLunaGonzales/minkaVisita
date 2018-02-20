<?php

require("../../conexion.inc");
$results = mysql_query("select a.cod_ciclo, b.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b where a.codigo_gestion = b.codigo_gestion and a.codigo_linea = 1021 and b.estado = 'Activo' ORDER BY 1 DESC");
//echo("select a.cod_ciclo, b.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b where a.codigo_gestion = b.codigo_gestion and a.codigo_linea = 1021 and b.estado = 'Activo' ORDER BY 1 DESC");
//$json = array();
while (is_resource($results) && $row = mysql_fetch_object($results)) {
//    $json[] = '{"id" : "' . $row->cod_ciclo . "-" . $row->codigo_gestion . '", "label" : "' . $row->cod_ciclo . "-" . $row->nombre_gestion . '"}';
//    $json[] = '"' . $row->nombre . '"';
    $response[$row->cod_ciclo . "-" . $row->codigo_gestion . "-" . $_GET['especialidades'] . "-" . $_GET['territorios'] ] = $row->cod_ciclo . "-" . $row->nombre_gestion;
}
print json_encode($response);
?>