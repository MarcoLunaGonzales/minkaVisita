<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	//sacamos los datos
	$sql="insert into zona_viaje_visitador values('$j_funcionario')";
	$resp=mysql_query($sql);
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_funcionarios_regional.php';
			</script>";
?>