<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require('estilos.inc');
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from grilla where codigo_grilla=$vector[$i]";
		$resp=mysql_query($sql);
		$sql1="delete from grilla_detalle where codigo_grilla=$vector[$i]";
		$resp1=mysql_query($sql1);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_grillas.php?cod_ciudad=$cod_ciudad';
			</script>";	
  
?>