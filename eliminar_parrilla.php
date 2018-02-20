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
		$sql="delete from parrilla where codigo_parrilla=$vector[$i]";
		$resp=mysql_query($sql);
		$sql1="delete from parrilla_detalle where codigo_parrilla=$vector[$i]";
		$resp1=mysql_query($sql1);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_parrillas_ciclos_detalle.php?cod_especialidad=$cod_especialidad&ciclo_trabajo=$ciclo_trabajo';
			</script>";	
?>