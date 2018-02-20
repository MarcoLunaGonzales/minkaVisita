<?php
/**
 * Desarrollado por Datanet.
 * @autor: Marco Antonio Luna Gonzales
 * @copyright 2005 
*/
//se requiere todavia el codigo del visitador otorgado por un cookie
  require("conexion.inc");
  $fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
  //formamos los ordenes de visita de acuerdo a la fecha
  $sql_aux="select * from contactos where fecha='$fecha_real' order by orden_visita desc";
  $resp_aux=mysql_query($sql_aux);
  $num_fil_aux=mysql_num_rows($resp_aux);
  if($num_fil_aux==0)
  {
  		$orden_visita=1;
  }
  else
  {
  		$dat_aux=mysql_fetch_array($resp_aux);
  		$orden_visita=$dat_aux[4]+1;
  }
    
  $sql="insert into contactos values ('','1-2006','$fecha_real','$turno','$orden_visita','$global_visitador','$cod_med','$cod_especialidad','$cat_med','$cod_zona')";
  $resp=mysql_query($sql);
  header("location:creacion_contactos.php");
?>