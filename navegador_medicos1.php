<?php

	require("conexion.inc");
	require("estilos_administracion.inc");
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td><center>Medicos Listado Madre</center></td></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='40%'>";
	echo "<tr><th>Territorio</th><th>Ver Activos</th><th>Ver Inactivos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr>
		<td>$p_agencia</td>
		<td><a href='navegador_medicos2.php?cod_ciudad=$p_cod_ciudad&estado=1'>Activos>></a></td>
		<td><a href='navegador_medicos2.php?cod_ciudad=$p_cod_ciudad&estado=0'>Inactivos>></a></td>
		</tr>";
	}
	echo "</table>";
	echo "<br>";

?>