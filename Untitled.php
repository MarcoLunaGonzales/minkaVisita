<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require('conexion.inc');
	require('estilos.inc');
	$sql="select * from contactos where cod_visitador='$h_cod_vis'";
	$resp=mysql_query($sql);
	echo "<center><table border='1' class='texto'><tr><td>Agencia $h_agencia</td><tr>";
	echo "<tr><td>Visitador $h_cod_vis</td></tr></table></center>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_fecha=$dat[2];
		$p_turno=$dat[3];
		$p_orden_visita=$dat[4];
		$p_cod_medico=$dat[5];
		$p_cod_especialidad=$dat[6];
		$p_categoria_med=$dat[7];
		$p_cod_zona=$dat[8];
		
	}

?>