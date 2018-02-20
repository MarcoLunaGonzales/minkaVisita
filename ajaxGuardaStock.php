<?php
require('conexion.inc');
$codigo_muestra = $_GET['codigo'];
$tipoStock = $_GET['tipoStock'];
$cantidad = $_GET['cantidad'];
if ($tipoStock == 1) {
    $sqlUpd = "update muestras_medicas set stock_minimo='$cantidad' where codigo='$codigo_muestra'";
} 
if ($tipoStock == 2) {
    $sqlUpd = "update muestras_medicas set stock_reposicion='$cantidad' where codigo='$codigo_muestra'";
} 
if ($tipoStock == 3) {
    $sqlUpd = "update muestras_medicas set stock_maximo='$cantidad' where codigo='$codigo_muestra'";
} 
$respUpd = mysql_query($sqlUpd);

echo $cantidad;

?>