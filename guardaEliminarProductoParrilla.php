<?php
 /**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	$ciclo_trabajo=$_GET['ciclo_trabajo'];
	$codigo_linea=$_GET['codigo_linea'];
	$codigo_gestion=$_GET['codigo_gestion'];
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{	list($codMuestra,$codAgencia,$numVisita,$codGestion)=explode("|",$vector[$i]);
		$sqlParrilla="select distinct(p.codigo_parrilla) from parrilla p, parrilla_detalle pd where p.agencia='$codAgencia' 
					and p.codigo_linea=$codigo_linea and codigo_gestion=$codGestion and cod_ciclo=$ciclo_trabajo 
					and pd.codigo_muestra='$codMuestra' and p.numero_visita='$numVisita' and p.codigo_parrilla=pd.codigo_parrilla";
		$respParrilla=mysql_query($sqlParrilla);
		while($datParrilla=mysql_fetch_array($respParrilla)){
			$codParrilla=$datParrilla[0];
			echo $codParrilla." ";
			$sqlDel="delete from parrilla_detalle where codigo_parrilla='$codParrilla' and codigo_muestra='$codMuestra'";
			$respDel=mysql_query($sqlDel);
			//reordenamos las prioridades
			$sqlDetalle="select codigo_muestra from parrilla_detalle where codigo_parrilla='$codParrilla' order by prioridad";
			$respDetalle=mysql_query($sqlDetalle);
			$i=1;
			while($datDetalle=mysql_fetch_array($respDetalle)){
				$codigoMuestra=$datDetalle[0];
				$sqlUpd="update parrilla_detalle set prioridad=$i where codigo_parrilla='$codParrilla' and codigo_muestra='$codigoMuestra'";
				$respUpd=mysql_query($sqlUpd);
				$i++;
			}
		}

	}
	echo "<script language='Javascript'>
			alert('Los datos fueron eliminados.');
			location.href='navegador_parrillas_ciclos.php';
			</script>";


?>