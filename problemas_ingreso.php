<?php

	require("conexion.inc");
	//echo $usuario.$parametro.$email;
	echo "<link href='stilos.css' rel='stylesheet' type='text/css'>";
	$sql="select email from funcionarios where email='$email'";
	$resp=mysql_query($sql);
	$num_filas=mysql_num_rows($resp);
	if($num_filas==1)
	{	echo "<script language='Javascript'>
			alert('Los datos de ingreso fueron enviados a su cuenta de correo electronico.');
			location.href='index1.html';
			</script>";
	}
	else
	{	echo "<script language='Javascript'>
			location.href='index1.html';
			</script>";
	}

?>