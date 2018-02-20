<?php
require('conexion.inc');
$codigo_parrilla = $_GET['parrilla'];
$prioridad = $_GET['prioridad'];
$cantidad = $_GET['cantidad'];

$sqlUpd = "update parrilla_detalle set cantidad_muestra='$cantidad' where codigo_parrilla='$codigo_parrilla' and prioridad=$prioridad";
$respUpd = mysql_query($sqlUpd);

echo $cantidad;
?>