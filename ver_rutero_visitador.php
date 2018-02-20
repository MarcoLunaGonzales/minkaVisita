<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require('estilos.inc');
	require('conexion.inc');
	$sql="select codigo,nombre from funcionarios where cod_ciudad='$h_cod_agencia'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td><center>VISITADORES MEDICOS $h_agencia</center></td></tr></table><br></center>";
	echo "<center><table border='0' class='texto'>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_vis=$dat[0];
		$p_nom_vis=$dat[1];
		echo "<tr><td><li><a href='ver_rutero_ind.php?h_cod_vis=$p_cod_vis&h_agencia=$h_agencia'>$p_nom_vis</a></li></td></tr>";	
	}
  	echo "</table>";
?>