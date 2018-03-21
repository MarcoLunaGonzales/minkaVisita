<?php  
set_time_limit(0);
require("../../conexion.inc");

$contactos = $_REQUEST['contactos'];
$contactos = substr($contactos, 0, -1);
$contactos = explode("@", $contactos);

foreach ($contactos as $cadena) {
	$cadena = explode("|", $cadena);
	$sql1= mysql_query("UPDATE registro_no_visita set estado = 4 where cod_contacto = $cadena[0] and orden_visita = $cadena[1]");
	$sql2=mysql_query("UPDATE rutero_detalle set estado = 4 where cod_contacto = $cadena[0] and orden_visita = $cadena[1]");
}
echo json_encode("Bajas Aprobadas");

//ESTA MODIFICACION HACE QUE DIRECTAMENTE EL SUPERVISOR PUEDA APROBAR LOS REGISTROS DE BAJA.
/*foreach ($contactos as $cadena) {
	$cadena = explode("|", $cadena);
	$sql1= mysql_query("UPDATE registro_no_visita set estado = 1 where cod_contacto = $cadena[0] and orden_visita = $cadena[1]");
	$sql2=mysql_query("UPDATE rutero_detalle set estado = 2 where cod_contacto = $cadena[0] and orden_visita = $cadena[1]");
}
echo json_encode("Bajas Aprobadas");
*/
?>