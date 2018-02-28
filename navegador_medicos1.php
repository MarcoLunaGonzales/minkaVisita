<?php

	require("conexion.inc");
	require("estilos_administracion.inc");
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	
	echo "<h1>Editar Datos de Medico</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Territorio</th><th>Activos</th><th>Inactivos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr>
		<td>$p_agencia</td>
		<td align='center'><a href='navegador_medicos2.php?cod_ciudad=$p_cod_ciudad&estado=1'><img src='imagenes/flecha.png' width='40'></a></td>
		<td align='center'><a href='navegador_medicos2.php?cod_ciudad=$p_cod_ciudad&estado=0'><img src='imagenes/enter.png' width='40'></a></td>
		</tr>";
	}
	echo "</table>";
	echo "<br>";

?>