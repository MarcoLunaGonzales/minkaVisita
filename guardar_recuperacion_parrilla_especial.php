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

$sql="select p.codigo_parrilla_especial, p.cod_especialidad, p.codigo_linea, p.numero_visita, p.agencia, p.codigo_grupo_especial,
 p.muestras_extra, p.codigo_gestion from parrilla_especial p, grupo_especial g 
 where p.cod_ciclo='$ciclo_rec' and p.codigo_linea=$global_linea 
and p.codigo_gestion=1014 and p.codigo_grupo_especial=g.codigo_grupo_especial order by p.codigo_parrilla_especial";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{
  	$cod_parrilla=$dat[0];
  	$cod_espe=$dat[1];
  	$linea=$dat[2];
  	$num_vis=$dat[3];
	$agencia=$dat[4];
	$grupo_espe=$dat[5];
	$extras=$dat[6];
	$gestion=$dat[7];
	  	$sql_aux=mysql_query("select codigo_parrilla_especial from parrilla_especial order by codigo_parrilla_especial desc");
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
  	$sql_inserta="insert into parrilla_especial values($codigo_parrilla,'$ciclo_trabajo','$cod_espe',$linea,'$fecha_creacion','$fecha_creacion','$num_vis','$agencia','$grupo_espe','$extras','1014')";
	echo "INSERT CABECERA:   $sql_inserta<br>";
	$resp_inserta=mysql_query($sql_inserta);
  	$sql_detalle="select codigo_muestra, cantidad_muestra, codigo_material, cantidad_material, prioridad, observaciones,extra from parrilla_detalle_especial where codigo_parrilla_especial='$cod_parrilla' order by codigo_parrilla_especial";
	
	//echo "SELECT DETALLE:   $sql_detalle<br>";
  	
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
		$sql_inserta_detalle="insert into parrilla_detalle_especial values($codigo_parrilla,'$muestra',$cant_muestra,'$material',$cant_material,$prioridad,'$obs','$ext')";

		echo "INSERT DETALLE:   $sql_inserta_detalle<br>";

		$resp_inserta_detalle=mysql_query($sql_inserta_detalle);   
	}
  	
}
		echo "<script language='Javascript'>
			alert('La recuperacion de parrillas se realizo con satisfacción.');
			location.href='navegador_parrilla_especial_ciclos.php?ciclo_trabajo=$ciclo_trabajo';
		</script>";
?>