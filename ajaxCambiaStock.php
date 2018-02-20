<?php
require('conexion.inc');
$codigo_muestra=$_GET['codigo'];
$tipoStock=$_GET['tipoStock'];
$cantidad=$_GET['cantidad'];
$id=$_GET['id'];
echo "<input type='text' size='5' class='texto' onBlur='guardaAjaxStock(this, \"$codigo_muestra\", $tipoStock, \"$id\");' value='$cantidad'>";
?>