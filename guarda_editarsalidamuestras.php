<?php
require("conexion.inc");
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
$codigo=$codigo_registro;
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$hora_salida=date("H:i:s");
$vector_material=explode(",",$vector_material);
$vector_cantidades=explode(",",$vector_cantidades);
$vector_fecha_vencimiento=explode(",",$vector_fechavenci);
//restablecemos las cantidades sacadas de la tabla salida_detalle_ingreso
//borramos todos los registros que se hacen al sacar materiales
$sql_detalle="select cod_ingreso_almacen, material, cantidad_unitaria, nro_lote
					from salida_detalle_ingreso
					where cod_salida_almacen='$codigo_registro' and grupo_salida_ingreso=1";
$resp_detalle=mysql_query($sql_detalle);
while($dat_detalle=mysql_fetch_array($resp_detalle))
{	$codigo_ingreso=$dat_detalle[0];
	$material=$dat_detalle[1];
	$cantidad=$dat_detalle[2];
	$nro_lote=$dat_detalle[3];
	$sql_ingreso_cantidad="select cantidad_restante from ingreso_detalle_almacenes
							where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material'
									and nro_lote='$nro_lote'";
	$resp_ingreso_cantidad=mysql_query($sql_ingreso_cantidad);
	$dat_ingreso_cantidad=mysql_fetch_array($resp_ingreso_cantidad);
	$cantidad_restante=$dat_ingreso_cantidad[0];
	$cantidad_restante_actualizada=$cantidad_restante+$cantidad;
	$sql_actualiza="update ingreso_detalle_almacenes set cantidad_restante='$cantidad_restante_actualizada'
					where cod_ingreso_almacen='$codigo_ingreso' and cod_material='$material' and nro_lote='$nro_lote'";
	$resp_actualiza=mysql_query($sql_actualiza);
}
$sql_borrar_detalle_salida=mysql_query("delete from salida_detalle_almacenes where cod_salida_almacen='$codigo_registro'");
$sql_borrar_detalle_ingreso=mysql_query("delete from salida_detalle_ingreso where cod_salida_almacen='$codigo_registro'");
$borrar_detalle_visitador=mysql_query("delete from salida_detalle_visitador where cod_salida_almacen='$codigo_registro'");
//fin borrado
$sql_modifica_salida="update salida_almacenes set cod_tiposalida='$tipo_salida', hora_salida='$hora_salida',
					territorio_destino='$territorio', almacen_destino='$almacen', observaciones='$observaciones'
					where cod_salida_almacenes='$codigo_registro'";
$resp_modifica_salida=mysql_query($sql_modifica_salida);
//$sql_inserta=mysql_query("insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,1,'','',0,0,0,0,'','$nro_correlativo',0)");
if($funcionario!="")
{	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario,0,0)");
}
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$fecha_vencimiento=$vector_fechavenci[$i];
	$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
	$cantidad=$vector_cantidades[$i];
	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
	ingreso_almacenes i
	where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and id.cod_material='$cod_material' and id.cantidad_restante<>0 order by id.fecha_vencimiento";
	//echo $sql_detalle_ingreso;
	$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
	$cantidad_bandera=$cantidad;
	$bandera=0;
	while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
	{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
		$cantidad_restante=$dat_detalle_ingreso[1];
		$nro_lote=$dat_detalle_ingreso[2];
		if($bandera!=1)
		{
			if($cantidad_bandera>$cantidad_restante)
			{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','1')";
				$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
				$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
				$resp_upd_cantidades=mysql_query($upd_cantidades);
			}
			else
			{
				$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','1')";
				$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
				$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
				$bandera=1;
				$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
				$resp_upd_cantidades=mysql_query($upd_cantidades);
				$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
			}
		}
	}
	$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
}
echo "<script language='Javascript'>
			alert('Los datos se modificaron correctamente.');
			location.href='navegador_salidamuestras.php';
			</script>";
?>