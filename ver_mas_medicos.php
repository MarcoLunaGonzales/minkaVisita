<?php
	require("conexion.inc");
	require("estilos.inc");
	$sql="select * from medicos where cod_med='$h_cod_med' order by ap_pat_med";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'>";
	echo "<tr><td>Medicos Registrados</td></tr></table><br>";
	echo "<table border='0' class='texto'>";
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
		$p_hobbie=$dat[8];
		$p_est_civil=$dat[9];
		$p_nom_secre=$dat[10];
		$p_perfil=$dat[11];
		$p_nombre_com="$p_paterno $p_materno $p_nombres";
		echo "<tr><td>Nombres</td><td>$p_nombre_com</td></tr>";
		echo "<tr><td>Fecha Nacimiento</td><td>$p_fecha_nac</td></tr>";
		echo "<tr><td>Teléfono</td><td>$p_telf</td></tr>";
		echo "<tr><td>Telf. Celular</td><td>$p_celular</td></tr>";
		echo "<tr><td>Correo Electrónico</td><td>$p_email</td></tr>";
		echo "<tr><td>Hobbie</td><td>$p_hobbie</td></tr>";
		echo "<tr><td>Estado Civil</td><td>$p_est_civil</td></tr>";
		echo "<tr><td>Nombre Secretaria</td><td>$p_nom_secre</td></tr>";
		echo "<tr><td>Perfil Psicografico</td><td>$p_perfil</td></tr>";		
	}
	echo "</table></center>";
//<td><tr>$p_nombre_com</tr><tr>$p_fecha_nac</tr><tr>$p_telf</tr><tr>$p_celular</tr><tr>$p_email</tr><tr><a href='ver_mas_medicos.php?h_cod_med=$p_codigo'>->></a></tr><tr></tr></td>
?>