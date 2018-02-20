<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	if($usuario_rrhh!="")
	{	require("estilos_rrhh.php");
	}
	else
	{	require("estilos_administracion.inc");
	}
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td><center>Registro General de Funcionarios</center></td></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='20%'>";
	echo "<tr><th>Territorio</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td><li><a href='navegador_funcionarios.php?cod_ciudad=$p_cod_ciudad'>$p_agencia</a></li></td></tr>";
	}
	echo "</table>";
	echo "<br>";

?>