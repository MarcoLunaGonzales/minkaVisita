<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
	require("conexion.inc");
	require("estilos.inc");
	$sql="select * from medicos order by ap_pat_med";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td>Medicos Registrados</td></tr></table><br>";
	echo "<table border='1' class='texto'><tr><td>Nombre</td><td>Fecha de Nacimiento</td><td>Teléfono</td><td>Telf. Celular</td><td>Correo Electronico</td><td>Ver mas</td><td>Ver Especialidades</td></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$p_codigo=$dat[0];
		$p_paterno=$dat[1];
		$p_materno=$dat[2];
		$p_nombres=$dat[3];
		$p_fecha_nac=$dat[4];
		$p_telf=$dat[5];
		$p_celular=$dat[6];
		$p_email=$dat[7];
		$p_nombre_com="$p_paterno $p_materno $p_nombres";
		echo "<tr><td>$p_nombre_com</td><td>$p_fecha_nac</td><td>$p_telf</td><td>$p_celular</td><td>$p_email</td><td><a href='ver_mas_medicos.php?h_cod_med=$p_codigo'>->></a></td><td></td></tr>";
	}
	echo "</table></center>";
?>