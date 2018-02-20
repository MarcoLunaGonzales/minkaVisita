<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require("estilos.inc");
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td><center>Registro de Medicos<br> Ver por Agencia</center></td></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto'>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td><li><a href='navegador_medicos_lineas.php?cod_ciudad=$p_cod_ciudad'>$p_agencia</a></li></td></tr>";
	}
	echo "</table>";
	echo "<br>";

?>