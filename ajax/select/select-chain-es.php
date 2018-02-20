<?php

require("../../conexion.inc");
$results = mysql_query('select codigo_l_visita, nom_orden from lineas_visita where codigo_l_visita <> 0 order by 2');
//$json = array();
while (is_resource($results) && $row = mysql_fetch_object($results)) {
//    $json[] = '{"id" : "' . $row->codigo_l_visita . '", "label" : "' . $row->nombre . '"}';
//    $json[] = '"' . $row->nombre . '"';
    $response[$row->codigo_l_visita] = $row->nom_orden;
}
print json_encode($response);
?>