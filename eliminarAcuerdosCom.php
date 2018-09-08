<?php
	require("conexion.inc");
	require("estilos_administracion.inc");
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from acuerdos_comerciales where id_acuerdos=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegadorAcuerdosCom.php';
			</script>";


?>