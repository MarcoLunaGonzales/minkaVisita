<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
require("conexion.inc");
require("estilos_gerencia.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{	$sql="insert into grupo_especial_detalle_visitadores values($cod_grupo,$vector[$i])";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron se insertaron correctamente.');
			location.href='navegador_grupoespe_detallevisitadores.php?cod_grupo=$cod_grupo';
			</script>";
?>