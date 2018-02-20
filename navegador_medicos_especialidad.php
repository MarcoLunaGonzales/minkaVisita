<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form>";
	$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Ver Medicos de la Línea por Especialidades</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='40%'>";
	echo "<tr><th>Especialidad</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_espe=$dat[0];
		$nom_espe=$dat[1];
		echo "<tr><td>&nbsp;&nbsp;$nom_espe</td><td align='center'><a href='ver_medicos_especialidad.php?cod_espe=$cod_espe'>Ver Medicos >></a></td></tr>";
	}
	echo "</table></center><br>";
	require("home_regional1.inc");
	echo "</form>";
?>