<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require('estilos.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from parrilla_especial where codigo_parrilla_especial=$vector[$i]";
		$resp=mysql_query($sql);
		$sql1="delete from parrilla_detalle_especial where codigo_parrilla_especial=$vector[$i]";
		$resp1=mysql_query($sql1);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo';
			</script>";	
?>