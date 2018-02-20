<?php
require("conexion.inc");
require("estilos_almacenes.inc");
$sql="select cod_salida_almacenes from salida_almacenes order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
echo $codigo;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$vector_material=explode(",",$vector_material);
$vector_cantidades=explode(",",$vector_cantidades);	
$vector_fecha_vencimiento=explode(",",$vector_fechavenci);
if($funcionario!="")
{	$estado_salida=2;
	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario,0)");	
}
else
{	$estado_salida=0;
}
$sql_inserta=mysql_query("insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$territorio','$almacen','$observaciones',$estado_salida)");
if($funcionario!="")
{	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario)");	
}
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$cod_material1=$cod_material*1;
	if($cod_material1==0)
	{	$codigo_tipo_material=1;
	}
	else
	{	$codigo_tipo_material=2;
	}
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material','$fecha_sistema_vencimiento',$cantidad,'$codigo_tipo_material')");
}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_salidaalmacenes.php';
			</script>";
?>