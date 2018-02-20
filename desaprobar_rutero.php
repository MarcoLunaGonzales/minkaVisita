<?php  
set_time_limit(0);
require("conexion.inc");


$ruteros_a_desaprobar = "9375";
$ruteros_a_desaprobar = explode(",", $ruteros_a_desaprobar);

foreach ($ruteros_a_desaprobar as $key => $value) {
	$cod_contactos = '';
	$sql_contactos = mysql_query("SELECT cod_contacto from rutero_maestro_aprobado where cod_rutero = $value");
	while ($row_contacto = mysql_fetch_array($sql_contactos)) {
		$cod_contactos .= $row_contacto[0].",";
	}
	$cod_contactos = substr($cod_contactos, 0, -1);
	$sql_delete1 = mysql_query("DELETE from rutero_maestro_detalle_aprobado where cod_contacto in ($cod_contactos)");
	$sql_delete2 = mysql_query("DELETE from rutero_maestro_aprobado where cod_rutero = $value and cod_contacto in ($cod_contactos)");
	$sql_delete3 = mysql_query("DELETE from rutero_maestro_cab_aprobado where cod_rutero = $value");
	$sql_update  = mysql_query("UPDATE rutero_maestro_cab set estado_aprobado = 0 where cod_rutero = $value");
}
echo "Ok";
?>