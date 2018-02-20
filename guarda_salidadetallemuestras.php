<?php
require("conexion.inc");
$sql="select cod_salida_almacenes from salida_almacenes order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo_salida_almacen=1000;
}
else
{	$codigo_salida_almacen=$dat[0];
	$codigo_salida_almacen++;
}
$sql_borra=mysql_query("delete from salida_detalle_ingreso where cod_salida_almacen=$codigo_salida_almacen and material='$material'");
for($i=1;$i<$numero_ingresos;$i++)
{	$var_cantidad="cantidad$i";
	$var_cod_ingreso="cod_ingreso$i";
	$valor_cantidad=$$var_cantidad;
	$valor_cod_ingreso=$$var_cod_ingreso;
	if($valor_cantidad!=0)
	{	$sql_inserta=mysql_query("insert into salida_detalle_ingreso values($codigo_salida_almacen,$valor_cod_ingreso,'$material',$valor_cantidad,1)");
	}	
}
echo "<script language='JavaScript'>
	window.close();
</script>";  
?>