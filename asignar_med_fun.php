<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$j_funcionario'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	
	echo "<h1>Asignar Medicos<br>Visitador: $nombre_funcionario</h1>";
	
	echo "<center><table class='texto'>";
	echo "<tr><th>Ver Medicos Por</th><th>&nbsp;</th></tr>";
	echo "<tr><td>&nbsp;Alfabeto</td><td align='center'><a href='asignar_med_alfabeto.php?visitador=$j_funcionario'>Ver >></a></td></tr>";
	//echo "<tr><td>&nbsp;Categoria</td><td align='center'><a href='asignar_med_categoria.php?visitador=$j_funcionario'>Ver >></a></td></tr>";
	//echo "<tr><td>&nbsp;Especialidad</td><td align='center'><a href='asignar_med_especialidad.php?visitador=$j_funcionario'>Ver >></a></td></tr>";
	echo "<tr><td>&nbsp;Codigo</td><td align='center'><a href='asignar_med_ruc.php?visitador=$j_funcionario'>Ver >></a></td></tr>";
	echo "</table>";
	
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";	
?>