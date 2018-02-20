<?php

	require("conexion.inc");
	require("estilos_administracion.inc");
	$codigoLinea=$_GET['codigoLinea'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="insert into funcionarios_lineas values('$vector[$i]' , '$codigoLinea')";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron guardados.');
			location.href='navLineasFuncionarios.php?codigoLinea=$codigoLinea';
			</script>";


?>