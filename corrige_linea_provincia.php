<?php
require('conexion.inc');
$sql="select cod_med from categorias_lineas where codigo_linea='1009'";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{
	$codigo_medico=$dat[0];
	$sql_ver="select rd.cod_med
from rutero_maestro_cab rc, rutero_maestro r, rutero_maestro_detalle rd
where r.cod_rutero=rc.cod_rutero and rc.codigo_linea='1009' 
and r.cod_contacto=rd.cod_contacto and rd.cod_med='$codigo_medico'";
	$resp_ver=mysql_query($sql_ver);
	$num_filas=mysql_num_rows($resp_ver);
	if($num_filas==0)
	{	$del=mysql_query("delete from medico_asignado_visitador where cod_med='$codigo_medico' and codigo_linea='1009'");
		$del=mysql_query("delete from categorias_lineas where cod_med='$codigo_medico' and codigo_linea='1009'");
	}
}
?>