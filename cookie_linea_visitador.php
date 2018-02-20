<?php

	require("conexion.inc");
	if($linea==0)
	{	$sql_linea="select codigo_linea from funcionarios_lineas where codigo_funcionario='$global_visitador'";
		$resp_linea=mysql_query($sql_linea);
		$dat=mysql_fetch_array($resp_linea);
		$linea=$dat[0];
		setcookie("global_zona_viaje",1);
		setcookie("global_linea",$linea);
	}
	else
	{	setcookie("global_zona_viaje",0);
		setcookie("global_linea",$linea);
	}
	//-header("location:indexVisitador.php");
	
	echo "<script>window.top.location.href='indexVisitador.php';</script>";
	
?>