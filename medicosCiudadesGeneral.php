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
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Medicos por Territorio<br><strong>$nombre_linea</strong></center></td></tr></table><br>";
	echo "<center><table  width='60%' border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Territorio</th><th>&nbsp;</th></tr>";
	//echo "<tr><td>Nacional</td><td><a href='grilla_nacional.php'>Ver >></a></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td align='left'>&nbsp;&nbsp;$p_agencia</td>
		<td align='center'>
		<a href='anadir_medico_linea_general.php?codCiudadGlobal=$p_cod_ciudad&ver=1'>Lista Orden Alfabetico>></a>
		</td>
		<td align='center'>
		<a href='anadir_medico_linea_general.php?codCiudadGlobal=$p_cod_ciudad&ver=2'>Lista Orden Codigo>></a>
		</td>
		</tr>";
	}
	echo "</table>";
	echo "<br>";
	require('home_central1.inc');
?>