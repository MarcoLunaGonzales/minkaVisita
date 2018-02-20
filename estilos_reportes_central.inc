<?php

echo"<link href='stilos.css' rel='stylesheet' type='text/css'>";  

date_default_timezone_set('America/La_Paz');

$fecha_reporte=date("d/m/Y");

echo "<center><table border=0 cellspacing='0' width='100%' class='linea'><tr><td width='10%'>
<img src='imagenes/logoMinka.png' width='100'>
<img src='imagenes/crespal.png' width='160' >
</td><th width='80%'>
<br>Fecha de Reporte: $fecha_reporte<td width='10%'></td></tr></table></center><br>";

?>