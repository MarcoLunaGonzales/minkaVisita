<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require('estilos.inc');
	require('conexion.inc');
	$sql="select cod_ciudad, descripcion from ciudades";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td><center>RUTEROS MEDICOS POR AGENCIAS</center></td></tr></table><br>";
	echo "<center><table border='0' class='texto'>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td><li><a href='ver_rutero_visitador.php?h_cod_agencia=$p_cod_ciudad&h_agencia=$p_agencia'>$p_agencia</a></li></td></tr>";
	}
	echo "</table>";
?>