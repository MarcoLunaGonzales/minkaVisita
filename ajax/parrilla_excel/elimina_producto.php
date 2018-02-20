<?php

set_time_limit(0);
require("../../conexion.inc");

$ciclo = $_POST['ciclo'];
$gestion = $_POST['gestion'];
$producto = $_POST['producto'];

$sql_id = mysql_query("SELECT id from asignacion_mm_excel where ciclo = $ciclo and gestion = $gestion");
$id = mysql_result($sql_id, 0, 0);

$sql_update = mysql_query("DELETE from asignacion_mm_excel_detalle where id = $id and producto = '$producto' ");
?>