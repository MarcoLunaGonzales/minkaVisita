<?php

	require("conexion.inc");
	$sql="select * from  especialidades where cod_especialidad='$codespecialidad'";
	$resp=mysql_query($sql);
	$num_filas=mysql_num_rows($resp);
	if($num_filas >=1)
	{  //echo $num_filas;
		header("location:navegador_especialidades.php?mensaje=001");
	}
	else
	{
		$sql1="insert into especialidades values('$codespecialidad','$descespecialidad')";
		$resp=mysql_query($sql1);
		header("location:navegador_especialidades.php");
	}
	
	
?>