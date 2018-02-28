<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require("estilos_gerencia.inc");
	
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<h1>Asignar/Quitar Medicos de Lineas<br>$nombre_linea</h1>";

	echo "<center><table class='texto'>";
	echo "<tr><th>Territorio</th><th>Alfabetico</th><th>Codigo</th></tr>";
	//echo "<tr><td>Nacional</td><td><a href='grilla_nacional.php'>Ver >></a></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td align='left'>&nbsp;&nbsp;$p_agencia</td>
		<td align='center'>
		<a href='anadir_medico_linea_general.php?codCiudadGlobal=$p_cod_ciudad&ver=1'><img src='imagenes/flecha.png' width='40'></a>
		</td>
		<td align='center'>
		<a href='anadir_medico_linea_general.php?codCiudadGlobal=$p_cod_ciudad&ver=2'><img src='imagenes/flecha.png' width='40'></a>
		</td>
		</tr>";
	}
	echo "</table>";
	echo "<br>";
	require('home_central1.inc');
?>