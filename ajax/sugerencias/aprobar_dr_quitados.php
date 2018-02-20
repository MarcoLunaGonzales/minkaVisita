<?php  
set_time_limit(0);
require("../../conexion.inc");

$contactos = $_REQUEST['contactos'];
$contactos = substr($contactos, 0, -1);
$contactos = explode("@", $contactos);

foreach ($contactos as $cadena) {
	$cadena = explode("-", $cadena);
	$sql1= mysql_query("UPDATE muestras_quitadas_sugeridas set estado = 1 where muestra_mm = '$cadena[1]' and cod_med = $cadena[0] and cod_visitador = $cadena[2]");
	$sql_insert =  mysql_query("INSERT into muestras_negadas (cod_med,codigo_muestra,codigo_linea) values ($cadena[0],'$cadena[1]',1021)");
}

echo json_encode("Muestras quitadas Satisfactoriamente");
?>