<?php
require('conexion.inc');
$fechaIni=$_GET['fechaIni'];
$nroMeses=$_GET['nroMeses'];

$fechaFin=date('Y-m-d',strtotime($fechaIni.'+'.$nroMeses.' month'));
$fechaFin=date('Y-m-d',strtotime($fechaFin.'-1 day'));

echo "<input type='date' name='fecha_final' id='fecha_final' value='$fechaFin' min='$fechaFin' max='$fechaFin' class='textograndenegro'>";

?>