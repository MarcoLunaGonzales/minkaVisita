<?php

require("../../conexion.inc");

$cicloTrabajo=1;
$gestionTrabajo=1011;

$cicloDestino=2;

$territorioOrigen=123;
$territorioDestino=123;
$lineaOrigen=1021;
$lineaDestino=1021;



$borrar=0;
$fecha_creacion=date("Y-m-d");
//borramos todo de la regional destino
if($borrar==1){
	$sqlborrarDetalle="delete from parrilla_detalle where codigo_parrilla in (select codigo_parrilla from parrilla
							where cod_ciclo=$cicloDestino and codigo_gestion=$gestionTrabajo and codigo_linea=$lineaOrigen
							 and agencia=$territorioOrigen)";
	$respBorrar=mysql_query($sqlborrarDetalle);

	$sqlborrar="delete from parrilla
							where cod_ciclo=$cicloDestino and codigo_gestion=$gestionTrabajo and codigo_linea=$lineaOrigen
							 and agencia=$territorioOrigen";
	$respBorrar=mysql_query($sqlborrar);
}



$sql="select codigo_parrilla,cod_especialidad,categoria_med,codigo_linea,numero_visita,agencia,
			codigo_l_visita,muestras_extra,codigo_gestion from parrilla 
			where cod_ciclo='$cicloTrabajo' and codigo_gestion='$gestionTrabajo' 
			and agencia='$territorioOrigen' and codigo_linea=$lineaOrigen order by codigo_parrilla";

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
  	$sql_inserta="insert into parrilla values
  	($codigo_parrilla,'$cicloDestino','$cod_espe','$categoria',$lineaDestino,'$fecha_creacion',
  	'$fecha_creacion','$num_vis','$territorioDestino','$codigo_l_visita','$extras','$gestion',0)";

	
	echo $sql_inserta."<br />";
	

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
		echo "Copiado";
?>