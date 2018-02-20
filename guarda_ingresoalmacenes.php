<?php
require("conexion.inc");
require("estilos_almacenes_central.inc");
$sql="select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
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
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$vector_material=explode(",",$vector_material);
$vector_nrolote=explode(",",$vector_nrolote);
$vector_fechavenci=explode(",",$vector_fechavenci);
$vector_cantidades=explode(",",$vector_cantidades);	
$sql_inserta=mysql_query("insert into ingreso_almacenes values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$observaciones',1,0)");
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$cod_material1=$cod_material*1;
	if($cod_material1==0)
	{	$codigo_tipo_material=1;
	}
	else
	{	$codigo_tipo_material=2;
	}
	$numero_lote=$vector_nrolote[$i];
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_inserta2=mysql_query("insert into ingreso_detalle_almacenes values($codigo,'$cod_material','$numero_lote','$fecha_sistema_vencimiento',$cantidad,$codigo_tipo_material)");
}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomuestras.php';
			</script>";
?>