<?php

	require("conexion.inc");
	
		$sql1="update especialidades set desc_especialidad='$descespecialidad' where  cod_especialidad='$codespecialidad' ";
		$resp=mysql_query($sql1);
		header("location:navegador_especialidades.php");


?>