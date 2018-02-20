<?php  
set_time_limit(0);
require("../../conexion.inc");

$contactos = $_REQUEST['contactos'];
$contactos = substr($contactos, 0, -1);
$contactos = explode("@", $contactos);

foreach ($contactos as $cadena) {
	$cadena = explode("-", $cadena);
	$sql1= mysql_query("UPDATE muestras_agregadas_sugeridas set estado = 4 where muestra_mm = '$cadena[1]' and cod_med = $cadena[0] and cod_visitador = $cadena[2]");
}

echo json_encode("Sugerencias Rechazadas");
?>