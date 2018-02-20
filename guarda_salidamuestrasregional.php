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
{	
	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario,0)");	
}
$sql_inserta=mysql_query("insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$territorio','$almacen','$observaciones',0,1,'','',0,'')");
if($funcionario!="")
{	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario)");	
}
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_detalle_ingreso="select cod_ingreso_almacen, cantidad_restante from ingreso_detalle_almacenes 
	where cod_material='$cod_material' and cantidad_restante<>0 order by cod_ingreso_almacen";
	$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
	$cantidad_bandera=$cantidad;
	$bandera=0;
	while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
	{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
		$cantidad_restante=$dat_detalle_ingreso[1];
		if($cantidad_bandera>$cantidad_restante)
		{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$cantidad_restante','1')";
			$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
			$cantidad_bandera=$cantidad-$cantidad_restante;
			$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
			$resp_upd_cantidades=mysql_query($upd_cantidades);
		}
		else
		{	if($bandera!=1)
			{
				$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$cantidad_bandera','1')";
				$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
				$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
				$bandera=1;
				$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material'";
				$resp_upd_cantidades=mysql_query($upd_cantidades);
			}
		}
	}
	$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
	
}
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_salidamuestrasregional.php';
			</script>";
?>