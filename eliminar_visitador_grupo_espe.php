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
	{	$sql="delete from grupo_especial_detalle_visitadores where codigo_grupo_especial=$cod_grupo and codigo_funcionario=$vector[$i]";
		$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_grupoespe_detallevisitadores.php?cod_grupo=$cod_grupo';
			</script>";
?>