<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	require("conexion.inc");
	require("estilos.inc");
	$sql="select * from ciclos order by cod_ciclo";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Editar Ciclos</td></tr></table></center><br>";
	echo "<center><table border='0' class='texto'>";
	echo "<tr><td><strong>Nombre Ciclo</td><td><strong>Fecha de Inicio</td><td><strong>Fecha de Fin</td><td></td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_inicio=$dat[1];
		$fecha_fin=$dat[2];
		echo "<tr><td>$codigo</td><td>$fecha_inicio</td><td>$fecha_fin</td><td><a href='editar_ciclos.php?cod_ciclo=$codigo'>Editar>></a></td></tr>";
	}
	echo "</table></center>";
?>