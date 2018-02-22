<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	$sql_lineas="select nombre_linea from lineas where codigo_linea='$linea_importada'";
	$resp_lineas=mysql_query($sql_lineas);
	$dat_lineas=mysql_fetch_array($resp_lineas);
	$nombre_linea=$dat_lineas[0];
	echo "<center><table border='0' class='textotit'><tr><th>Importar Rutero Maestro<br>Visitador: $nombre_funcionario<br>Línea a importar el rutero maestro: $nombre_linea</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='45%'>";
	echo "<tr><th colspan='2'>Seleccione el visitador del cual desea importar el rutero maestro:</th></tr>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres from funcionarios f, funcionarios_lineas fl
				where f.codigo_funcionario=fl.codigo_funcionario and f.cod_cargo='1011' and f.cod_ciudad='$global_agencia' and fl.codigo_linea='$linea_importada' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat=mysql_fetch_array($resp_visitador))
	{	$codigo_funcionario=$dat[0];
		$nombre_funcionario="$dat[1] $dat[2] $dat[3]";
		echo "<tr><td>&nbsp$nombre_funcionario</td><td align='center'><a href='importar_rutero_visitador.php?j_funcionario=$visitador&linea_importada=$linea_importada&visitador_importado=$codigo_funcionario'>Importar >></a></td></tr>";	
	}
	echo "</table><br>";
	echo"\n<table align='center'><tr><td><a href='importar_rutero_maestro.php?j_funcionario=$visitador'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";	
?>