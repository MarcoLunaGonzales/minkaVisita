<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$j_funcionario'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	
	echo "<h1>Importar Rutero Maestro<br>Visitador: $nombre_funcionario</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th colspan='2'>Seleccione la Linea de la que desea importar el rutero maestro:</th></tr>";
	$sql_lineas="select * from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
	$resp_lineas=mysql_query($sql_lineas);
	while($dat=mysql_fetch_array($resp_lineas))
	{	$codigo_linea=$dat[0];
		$nombre_linea=$dat[1];
		echo "<tr><td>&nbsp$nombre_linea</td><td align='center'>
		<a href='importar_rutero_linea.php?visitador=$j_funcionario&linea_importada=$codigo_linea'><img src='imagenes/go.png' width='40'></a></td></tr>";	
	}
	echo "</table>";
	echo"\n<table align='center'><tr><td><a href='navegador_funcionarios_regional.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";	
?>