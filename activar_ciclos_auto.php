<?php
require("conexion.inc");
$fecha=date("Y-m-d");
echo $fecha;
$sql="update ciclos set estado='Activo' where fecha_ini='$fecha'";
$resp=mysql_query($sql);
$sql_cerrar="update ciclos set estado='Cerrado' where fecha_fin='$fecha'";
$resp_cerrar=mysql_query($sql_cerrar);
?>