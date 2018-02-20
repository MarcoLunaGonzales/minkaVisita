<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_visitador.inc");
	$sql_aux=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$global_linea'");
	$dat_aux=mysql_fetch_array($sql_aux);
	$codigo_ciclo_activo=$dat_aux[0];
	$sql="select dia_contacto, fecha from contactos where ciclo='$codigo_ciclo_activo' and cod_visitador='$global_visitador'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Registro de Rutero Medico x Dia</td></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' cellspacing='0' width='50%'>";
	echo "<tr><th>Dia Contacto</th><th>Fecha</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$dia_contacto=$dat[0];
		$fecha_contacto=$dat[1];
		echo "<tr><td align='center' class='texto'>$dia_contacto</td><td align='center'>$fecha_contacto</td><td align='center'><a href='detalle_contactos_dia.php?fecha_contacto=$fecha_contacto&dia_contacto=$dia_contacto'>Ver >></a></td></tr>";
	}
	echo "</table></center><br>";
	
?>