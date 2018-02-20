<?php

	require("conexion.inc");
	require("estilos_administracion.inc");
	$codigoLinea=$_GET['codigoLinea'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from funcionarios_lineas where codigo_linea='$codigoLinea' and codigo_funcionario=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navLineasFuncionarios.php?codigoLinea=$codigoLinea';
			</script>";


?>