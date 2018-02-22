<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$visitador=$visitador;
	//formamos el nombre del funcionario
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	//fin formar nombre funcionario
	echo "<form>";
	$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Asignar Medicos<br>Listado por Especialidades<br>Visitador:$nombre_funcionario</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='50%'>";
	echo "<tr><th>Especialidad</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_espe=$dat[0];
		$nom_espe=$dat[1];
		echo "<tr><td align='center'>$nom_espe</td><td align='center'><a href='asignar_medicos_especialidad.php?cod_espe=$cod_espe&visitador=$visitador'>Ver Medicos >></a></td></tr>";
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "</form>";
?>