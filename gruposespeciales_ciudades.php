<?php
/**
 *  @autor: Marco Antonio Luna Gonzales
 *  Sistema de Visita Médica
 *  * @copyright 2005
*/
	require("conexion.inc");
	require("estilos_gerencia.inc");
	$codigo_linea=$_GET['codigo_linea'];
	$resp_nombre_linea=mysql_query("select nombre_linea from lineas where codigo_linea=$codigo_linea");
	$dat_nombre_linea=mysql_fetch_array($resp_nombre_linea);
	$nombre_linea=$dat_nombre_linea[0];
	
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Grupos Especiales por Territorio<br>Linea: <strong>$nombre_linea</strong></center></td></tr></table><br>";
	echo "<center><table  width='30%' border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Territorio</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td align='left'>&nbsp;&nbsp;$p_agencia</td><td align='center'>
		<a href='navegador_grupo_especial.php?cod_ciudad=$p_cod_ciudad&codigo_linea=$codigo_linea'>Ver >></a></td></tr>";
	}
	echo "</table>";
	echo "<br>";
	require('home_central1.inc');
?>