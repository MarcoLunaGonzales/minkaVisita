<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
require("conexion.inc");
require("estilos_regional_pri.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{	$sql="insert into medico_asignado_visitador values('$vector[$i]','$visitador','$global_linea')";
	 	$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los Medicos fueron asignados correctamente.');
			location.href='medicos_asignados.php?visitador=$visitador';
			</script>";
?>