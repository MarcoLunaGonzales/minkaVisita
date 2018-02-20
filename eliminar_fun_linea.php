<?php
/**
 * @autor: Marco Antonio Luna Gonzales
 * SISTEMA HERMES
 * * @copyright 2008 
*/ 
	require("conexion.inc");
	require("estilos_administracion.inc");
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from funcionarios_lineas where codigo_funcionario='$j_funcionario' and codigo_linea='$vector[$i]'";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='anadir_funcionario_linea.php?j_funcionario=$j_funcionario';
			</script>";	
?>