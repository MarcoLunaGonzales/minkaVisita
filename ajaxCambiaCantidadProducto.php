<?php
require('conexion.inc');
$codigo_parrilla=$_GET['parrilla'];
$prioridad=$_GET['prioridad'];
$codigo_linea=$_GET['linea'];
$id=$_GET['id'];
$cantidad=$_GET['cantidad'];

echo "<input type='text' size='2' class='texto' onBlur='guardaAjaxCambiaCantidad(this, $codigo_parrilla, $prioridad, $id);' value='$cantidad'>";
?>