<?php
	require("conexion.inc");
	require("estilos_gerencia.inc");
	
	$sql="select cod_ciudad,descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td align='center'>Territorios</center></td></tr></table><br>";
	echo "<center><table  width='30%' border='1' cellspacing='0' class='texto'>";
	echo "<tr><th>Territorio</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_cod_ciudad=$dat[0];
		$p_agencia=$dat[1];
		echo "<tr><td align='left'>&nbsp;&nbsp;$p_agencia</td><td align='center'>
		<a href='cookieTerritoriosMedicos.php?cod_ciudad=$p_cod_ciudad'>Ver Medicos>></a></td></tr>";
	}
	echo "</table>";
?>