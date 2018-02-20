<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
  //arreglamos la fecha 00/00/0000 a 0000-00-00
  $fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
  require('conexion.inc');
  //creamos el vector $muestras con las muestras enviadas
  //creamos el vector $cantidades con las cantidades enviadas de las muestras
  $muestras=explode(",",$vec_muestras);
  $cantidades=explode(",",$vec_cant);
  $num_elementos=sizeof($muestras);
  $sql=mysql_query("select * from contactos");
  if(mysql_num_rows($sql)==0)
  {
  	$p_codigo_contacto=100;
  }
  else
  {
  	$dat=mysql_fetch_array($sql);
	$p_codigo_contacto=$dat[0];
	$p_codigo_contacto++;
  }
  echo $p_codigo_contacto;
  $sql=mysql_query("insert into contactos values($p_codigo_contacto,1,'$fecha_real','$turno',1,'V1000','$cod_med','$cod_especialidad','$cat_med','$cod_zona')");
  for($i=0;$i<=$num_elementos-1;$i++)
  {	$sql=mysql_query("insert into visita values($p_codigo_contacto,'$muestras[$i]','$cantidades[$i]',0,'',0)");
  }
  //header("location:creacion_rutero.php")
?>