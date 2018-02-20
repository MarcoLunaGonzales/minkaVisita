<?php
require("conexionmysqli.php");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}

$bandera=0;

$sql="select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp=mysqli_query($enlaceCon,$sql);  
//mysql_query($sql);
$dat=mysqli_fetch_array($resp,MYSQLI_NUM);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$codigo=1;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
//echo "CODIGO: ".$codigo;



$sql="select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' and grupo_ingreso='1' order by cod_ingreso_almacen desc";
$resp=mysqli_query($enlaceCon, $sql);
$dat=mysqli_fetch_array($resp, MYSQLI_NUM);
$num_filas=mysqli_num_rows($resp);
if($num_filas==0)
{	$nro_correlativo=1;
}
else
{	$nro_correlativo=$dat[0];
	$nro_correlativo++;
}

$hora_sistema=date("H:i:s");
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$vector_material=explode(",",$vector_material);
$vector_nrolote=explode(",",$vector_nrolote);
$vector_fechavenci=explode(",",$vector_fechavenci);
$vector_cantidades=explode(",",$vector_cantidades);	

$txtSqlInsert="insert into ingreso_almacenes values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones',1,0,'$nota_entrega','$nro_correlativo',0,0)";
$sql_inserta=mysqli_query($enlaceCon, $txtSqlInsert);

echo "inserta: ".$sql_inserta;


for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$numero_lote=$vector_nrolote[$i];
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_inserta2=mysqli_query($enlaceCon, "insert into ingreso_detalle_almacenes values($codigo,'$cod_material','$numero_lote','$fecha_sistema_vencimiento',$cantidad,$cantidad,0)");
	echo "inserta2 ".$sql_inserta2;
}


/*
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomuestras.php';
			</script>";*/

?>