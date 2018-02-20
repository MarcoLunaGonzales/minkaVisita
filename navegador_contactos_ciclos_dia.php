<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_visitador.inc");
	$sql="select dia_contacto, fecha from contactos where ciclo='$ciclo_trabajo' and cod_visitador='$global_visitador'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td align='center'>Registro de Rutero Medico x Dia<br>Ciclo de Trabajo.$ciclo_trabajo</td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='50%'>";
	echo "<tr><th>Dia Contacto</th><th>Fecha</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$dia_contacto=$dat[0];
		$fecha_contacto=$dat[1];
		echo "<tr><td align='center' class='texto'>$dia_contacto</td><td align='center'>$fecha_contacto</td><td align='center'><a href='detalle_contactos_dia_ciclo.php?fecha_contacto=$fecha_contacto&dia_contacto=$dia_contacto&ciclo_trabajo=$ciclo_trabajo'>Ver >></a></td></tr>";
	}
	echo "</table></center><br>";
	
?>