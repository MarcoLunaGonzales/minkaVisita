<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Medicos Asignados<br>Visitador: $nombre_funcionario</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='30%'>";
	echo "<tr><th>Ver Medicos Por</th><th>&nbsp;</th></tr>";
	echo "<tr><td>&nbsp;Alfabeto</td><td align='center'><a href='medicos_asignados_alfabeto.php?visitador=$visitador'>Ver >></a></td></tr>";
	echo "<tr><td>&nbsp;Especialidad</td><td align='center'><a href='medicos_asignados_especialidad.php?visitador=$visitador'>Ver >></a></td></tr>";
	echo "</table>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";

?>