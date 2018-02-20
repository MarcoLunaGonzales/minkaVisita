<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require('conexion.inc');
	require('estilos.inc');
	$sql_aux=mysql_query("select cod_ciclo from ciclos where estado='activo'");
	$dat_aux=mysql_fetch_array($sql_aux);
	$ciclo_activo=$dat_aux[0];
	$sql="select * from contactos where cod_visitador='$h_cod_vis' and ciclo='$ciclo_activo' order by fecha";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td><center>Agencia $h_agencia</center></td><tr>";
	echo "<tr><td><center>Visitador $h_cod_vis</center></td></tr></table></center><br>";
	echo "<center><table class='texto' border='1'><tr><td><center>Fecha</center></td><td><center>Turno</center></td><td><center>Orden Visita</center></td><td><center>Medico</center></td><td><center>Especialidad</center></td><td><center>Categoria</center></td><td><center>Direccion</center></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_contacto=$dat[0];
		$p_fecha=$dat[2];
		$p_turno=$dat[3];
		$p_orden_visita=$dat[4];
		$p_cod_medico=$dat[6];
		$p_cod_especialidad=$dat[7];
		$p_categoria_med=$dat[8];
		$p_cod_zona=$dat[9];
		//formamos el nombre del medico
		$sql_aux="select * from medicos where cod_med='$p_cod_medico'";
		$resp_aux=mysql_query($sql_aux);
		$dat_aux=mysql_fetch_array($resp_aux);
		$p_nombre_medico=$dat_aux[3]." ".$dat_aux[1]." ".$dat_aux[2];
		//hasta esta parte formamos el nombre del medico	
		echo "<tr><td><center>$p_fecha</center></td><td><center>$p_turno</center></td><td><center>$p_orden_visita</center></td><td><center>$p_nombre_medico</center></td><td><center>$p_cod_especialidad</center></td><td><center>$p_categoria_med</center></td><td><center>$p_cod_zona</center></td></tr>";
	}
	echo "</table></center>";
?>