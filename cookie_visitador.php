<?php

	require("conexion.inc");
	$sql="select * from usuarios_sistema where usuario='$usuario' and contrasena='$clave'";
	$resp=mysql_query($sql);
	$num_filas=mysql_num_rows($resp);
	if($num_filas!=0)
	{
		setcookie("global_visitador",$usuario);
		header("location:frames_visitador.html");
	}
	else
	{
		echo "Usuario no registrado";
	}
	
?>