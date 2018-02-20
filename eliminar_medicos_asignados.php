<?php
 /**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		$sql="delete from medico_asignado_visitador where cod_med=$vector[$i] and codigo_visitador='$visitador' and codigo_linea='$global_linea'";
		
		$resp=mysql_query($sql);
		//desde aqui eliminamos al medico de los ruteros donde se pueda encontrar
		$cod_medaelim=$vector[$i];
		$sql_contactos="select rmd.cod_contacto, rmd.orden_visita from rutero_maestro rm, rutero_maestro_detalle rmd, rutero_maestro_cab rmc
		where rm.cod_contacto=rmd.cod_contacto and rm.cod_rutero=rmc.cod_rutero and
		rmd.cod_med='$cod_medaelim' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador'";
		$resp_contactos=mysql_query($sql_contactos);
		while($dat_contactos=mysql_fetch_array($resp_contactos))
		{	$cod_contacto=$dat_contactos[0];
			$orden_visita=$dat_contactos[1];
			//echo "$cod_contacto $orden_visita<br>";
			$elimina_medcontacto="delete from rutero_maestro_detalle where cod_contacto='$cod_contacto' and
			orden_visita='$orden_visita'";
			$resp_elimina_medcontacto=mysql_query($elimina_medcontacto);
			$orden_visita++;
			//sacamos el maximo orden de visita de los contactos
			$sql_max_contacto="select max(orden_visita) as maximo from rutero_maestro_detalle where cod_contacto='$cod_contacto'";
			$resp_max_contacto=mysql_query($sql_max_contacto);
			$dat_maximo=mysql_fetch_array($resp_max_contacto);
			$maximo=$dat_maximo[0];
			for($j=$orden_visita;$j<=$maximo;$j++)
			{	$orden_visita_actualizar=$j-1;
				$sql_actualiza="update rutero_maestro_detalle set orden_visita='$orden_visita_actualizar'
				where cod_contacto='$cod_contacto' and orden_visita='$j'";
				$resp_actualiza=mysql_query($sql_actualiza);
			}
		}
	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='medicos_asignados.php?visitador=$visitador';
			</script>";


?>