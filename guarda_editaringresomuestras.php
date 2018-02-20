<?php
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
$codigo=$codigo_registro;
$hora_sistema=date("H:m:s");
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$vector_material=explode(",",$vector_material);
$vector_nrolote=explode(",",$vector_nrolote);
$vector_fechavenci=explode(",",$vector_fechavenci);
$vector_cantidades=explode(",",$vector_cantidades);	
//$sql_inserta=mysql_query("insert into ingreso_almacenes values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones',1,0,'$nota_entrega','$nro_correlativo',0)");
$sql_update="update ingreso_almacenes set cod_tipoingreso='$tipo_ingreso', fecha='$fecha_real', hora_ingreso='$hora_sistema',
			observaciones='$observaciones', nota_entrega='$nota_entrega' where cod_ingreso_almacen='$codigo'";
$resp_update=mysql_query($sql_update);
$borra_detalle=mysql_query("delete from ingreso_detalle_almacenes where cod_ingreso_almacen='$codigo'");
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$numero_lote=$vector_nrolote[$i];
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_inserta2=mysql_query("insert into ingreso_detalle_almacenes values($codigo,'$cod_material','$numero_lote','$fecha_sistema_vencimiento',$cantidad,$cantidad,'0')");
}
echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_ingresomuestras.php';
			</script>";
?>