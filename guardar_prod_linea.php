<?php
require("conexion.inc");
require("estilos.inc");
$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql_inserta=mysql_query("insert into lineas_visita_detalle values($cod_linea_vis,'$vector[$i]')");	
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_l_visita_detalle.php?cod_linea_vis=$cod_linea_vis';
			</script>";
?>