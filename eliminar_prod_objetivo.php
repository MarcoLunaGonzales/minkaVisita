<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require('estilos_regional_pri.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from productos_objetivo where cod_med='$j_cod_med' and codigo_muestra='$vector[$i]' and codigo_linea='$global_linea'";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='producto_objetivo.php?j_cod_med=$j_cod_med';
			</script>";	
?>