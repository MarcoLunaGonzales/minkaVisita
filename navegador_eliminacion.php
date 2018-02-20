<?php

/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require("estilos_administracion.inc");
	$codigos=explode("|",$codigos_especialidades);
	$numcodigos=sizeof($codigos);
	$numcodigos=$numcodigos-2;
	for($i=0;$i<=$numcodigos;$i++)
	{
		$codespecialidad=$codigos[$i];
		$sql="delete from especialidades where cod_especialidad='$codespecialidad'";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_especialidades.php';
			</script>";
?>