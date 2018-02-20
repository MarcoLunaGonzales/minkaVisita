<?php
/**
 * @autor: Marco Antonio Luna Gonzales
 * SISTEMA HERMES
 * * @copyright 2008 
*/ 
	require("conexion.inc");
	require("estilos_administracion.inc");
	$cod_linea=$_GET["cod_linea"];
	$j_funcionario=$_GET["j_funcionario"];
	$sql="update funcionarios set codigo_lineaclave=$cod_linea where codigo_funcionario=$j_funcionario";
	$resp=mysql_query($sql);
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='anadir_funcionario_linea.php?j_funcionario=$j_funcionario';
			</script>";	
?>