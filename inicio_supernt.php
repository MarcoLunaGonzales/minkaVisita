<?php
	require("conexion.inc");
	require("estilos_regional.inc");
	//echo "<br><br><br><br><br><br><br>";
	//echo "<center><table class='texto'><tr><td><img src='imagenes/bienvenidos_jefe_regional.gif'></td></tr></table>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>Territorio</th><th>&nbsp;</th></tr>";
	if($global_usuario==1162 or $global_usuario=1052)
	{	$sql_territorio="select cod_ciudad, descripcion from ciudades where cod_ciudad<>115 order by descripcion";
	}
	else
	{	$sql_territorio="select cod_ciudad, descripcion from ciudades where tipo='0' order by descripcion";
	}
	$resp_territorio=mysql_query($sql_territorio);
	while($dat=mysql_fetch_array($resp_territorio))
	{	$codigo_ciudad=$dat[0];
		$descripcion_ciudad=$dat[1];
		echo "<tr><td>&nbsp;$descripcion_ciudad</td><td align='center'>
		<a href='cookie_ciudades.php?ciudad=$codigo_ciudad' target='_blank'>Ingresar >></a></td></tr>";
	}
	echo "</table></center><br>";	
?>
