<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require('conexion.inc');
	require('estilos.inc');
	$sql="select * from visita where cod_contacto='$h_cod_contacto'";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td><center>Agencia $h_agencia</center></td></tr>";
	echo "<tr><td><center>Visitador $h_cod_vis</center></td></tr>";
	echo "<tr><td><center>Medico $h_cod_medico</center></td></tr></table></center><br>"; 
	echo "<center><table border='1' class='texto'><tr><td><center>Muestra</center><td><center>Cantidad prefijada</center></td><td><center>Cantidad Entregada</center></td><td><center>Observaciones</center></td><td><center>Constancia de Entrega</center></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_muestra=$dat[1];
		$p_cant_a_ent=$dat[2];
		$p_cant_entregada=$dat[3];
		$p_obs=$dat[4];
		$p_constancia=$dat[5];
		echo "<tr><td><center>$p_muestra</center><td><center>$p_cant_a_ent</center></td><td><center>$p_cant_entregada</center></td><td><center>$p_obs</center></td><td><center>$p_constancia</center></td></tr>";
	}
	echo "</table></center>";
?>