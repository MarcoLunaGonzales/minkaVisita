<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2006
*/
require("conexion.inc");
require("estilos.inc");
$fecha_creacion=date("Y-m-d");
$ciclo_rec=$_GET['ciclo_rec'];
$gestion_rec=$_GET['gestion_rec'];
$ciclo_trabajo=$_GET['ciclo_trabajo'];
$gestion_trabajo=$_GET['gestion_trabajo'];

$sql="select codigo_parrilla,cod_especialidad,categoria_med,codigo_linea,numero_visita,agencia,codigo_l_visita,muestras_extra,codigo_gestion from parrilla where cod_ciclo='$ciclo_rec' and codigo_gestion='$gestion_rec' and codigo_linea=$global_linea order by codigo_parrilla";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{
  	$cod_parrilla=$dat[0];
  	$cod_espe=$dat[1];
  	$categoria=$dat[2];
  	$linea=$dat[3];
  	$num_vis=$dat[4];
	$agencia=$dat[5];
	$codigo_l_visita=$dat[6];
	$extras=$dat[7];
	$gestion=$dat[8];
	  	$sql_aux=mysql_query("select codigo_parrilla from parrilla order by codigo_parrilla desc");
		$num_filas=mysql_num_rows($sql_aux);
		if($num_filas==0)
		{	$codigo_parrilla=1000;
		}
		else
		{
			$dat_aux=mysql_fetch_array($sql_aux);
			$codigo_parrilla=$dat_aux[0];
			$codigo_parrilla++;
		}
  	$sql_inserta="insert into parrilla values($codigo_parrilla,'$ciclo_trabajo','$cod_espe','$categoria',$linea,'$fecha_creacion','$fecha_creacion','$num_vis','$agencia','$codigo_l_visita','$extras','$gestion_trabajo')";
	$resp_inserta=mysql_query($sql_inserta);
  	$sql_detalle="select codigo_muestra, cantidad_muestra, codigo_material, cantidad_material, prioridad, observaciones,extra from parrilla_detalle where codigo_parrilla='$cod_parrilla' order by codigo_parrilla";
  	$resp_detalle=mysql_query($sql_detalle);
  	while($dat_detalle=mysql_fetch_array($resp_detalle))
  	{
	    $muestra=$dat_detalle[0];
		$cant_muestra=$dat_detalle[1];
		$material=$dat_detalle[2];
		$cant_material=$dat_detalle[3];
		$prioridad=$dat_detalle[4];
		$obs=$dat_detalle[5];
		$ext=$dat_detalle[6];
		$sql_inserta_detalle="insert into parrilla_detalle values($codigo_parrilla,'$muestra',$cant_muestra,'$material',$cant_material,$prioridad,'$obs','$ext')";
		$resp_inserta_detalle=mysql_query($sql_inserta_detalle);   
	}
  	
}
		echo "<script language='Javascript'>
			alert('La recuperacion de parrillas se realizo con satisfacción.');
			location.href='navegador_parrillas.php';
		</script>";
?>