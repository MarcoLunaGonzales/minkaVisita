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
	{
		$cod_rutero_maestro=$vector[$i];
		$sql_rutero_maestro="select cod_contacto from rutero_maestro where cod_rutero='$cod_rutero_maestro'";
		$resp_rutero_maestro=mysql_query($sql_rutero_maestro);
		while($dat_rutero_maestro=mysql_fetch_array($resp_rutero_maestro))
		{	$cod_contacto=$dat_rutero_maestro[0];
			$sql_rutero_detalle="delete from rutero_maestro_detalle where cod_contacto='$cod_contacto'";
			$resp_rutero_detalle=mysql_query($sql_rutero_detalle); 
		}
		$sql_delete_rutero_maestro="delete from rutero_maestro where cod_rutero='$cod_rutero_maestro' and cod_visitador='$global_visitador'";
		$resp_delete_rutero_maestro=mysql_query($sql_delete_rutero_maestro);
		$sql_delete_rutero="delete from rutero_maestro_cab where cod_rutero='$cod_rutero_maestro' and cod_visitador='$global_visitador'";
		$resp_delete_rutero=mysql_query($sql_delete_rutero);
		//$sql="delete from rutero_maestro_cab where cod_rutero=$vector[$i] and cod_visitador='$global_visitador'";
		//$resp=mysql_query($sql);
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_rutero_maestro.php';
			</script>";


?>