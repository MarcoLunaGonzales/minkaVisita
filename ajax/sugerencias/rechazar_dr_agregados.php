<?php  
set_time_limit(0);
require("../../conexion.inc");

$contactos = $_REQUEST['contactos'];
$contactos = explode("-", $contactos);

$sql1= mysql_query("UPDATE muestras_agregadas_sugeridas set estado = 2 where muestra_mm = '$contactos[1]' and cod_med = $contactos[0] and cod_visitador = $contactos[2]");

echo json_encode("Sugerencia Rechazada");
?>