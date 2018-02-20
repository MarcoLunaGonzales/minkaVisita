<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	echo "<form>";
	$sql="select e.cod_especialidad,e.desc_especialidad from especialidades e, grupo_especial g 
			where e.cod_especialidad=g.cod_especialidad and g.codigo_linea='$global_linea' and g.agencia='$global_agencia' order by e.desc_especialidad";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><td>Grupos Especiales x Especialidad</td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0'>";
	echo "<tr><th>Especialidad</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$espe=$dat[1];
		echo "<tr><td align='center'>$espe</td><td align='center'><a href='navegador_grupoxesp_det.php?codigo=$codigo'>Ver >></a></td></tr>";
	}
	echo "</table></center><br>";
	echo "</form>";
?>