<?php
require("conexion.inc");
require("estilos_almacenes_central.inc");
$sql="select cod_salida_almacenes from salida_almacenes order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
echo $grupo_salida;
$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='$grupo_salida' order by cod_salida_almacenes desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$nro_correlativo=1;
}
else
{	$nro_correlativo=$dat[0];
	$nro_correlativo++;
}
$funcionario=$cod_visitador;
$sql_gestion="select codigo_gestion from gestiones where estado='Activo' and codigo_linea='$codigo_linea'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$codigo_gestion=$dat_gestion[0];
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$hora_salida=date("H:i:s");
$vector_material=explode(",",$vector_material);
$vector_cantidades=explode(",",$vector_cantidades);	
$vector_cantidadesplanificadas=explode(",",$vector_cantidadesplanificadas);
$cantidad_material=$nro_materiales;
$sql_almacen_destino="select cod_almacen where cod_ciudad='$cod_territorio'";
$resp_almacen_destino=mysql_query($sql_almacen_destino);
$dat_almacen_destino=mysql_fetch_array($resp_almacen_destino);
$almacen=$dat_almacen_destino[0];
$territorio=$cod_territorio;
$sql_inserta=mysql_query("insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,$grupo_salida,'','',0,0,0,0,'','$nro_correlativo',0)");
//insertamos el detalle de ciclos por visitador
$sql_codigo="select codigo_salida_visitador from salidas_visitador_ciclo order by codigo_salida_visitador desc";
$resp=mysql_query($sql_codigo);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo_salida_visitador=1;
}
else
{	$codigo_salida_visitador=$dat[0];
	$codigo_salida_visitador++;
}
$sql_inserta_visitador=mysql_query("insert into salidas_visitador_ciclo values($codigo_salida_visitador,$cod_visitador,$codigo_linea,$codigo_gestion,$codigo_ciclo,0,$grupo_salida)");
//fin detalle de ciclos por visitador
if($funcionario!="")
{	$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$funcionario)");	
}
for($i=0;$i<=$cantidad_material-1;$i++)
{	$cod_material=$vector_material[$i];
	$cantidad=$vector_cantidades[$i];
	$cantidad_planificada=$vector_cantidadesplanificadas[$i];
	if($cantidad!=0)
	{	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id, 
		ingreso_almacenes i
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and id.cod_material='$cod_material' and id.cantidad_restante<>0 order by id.fecha_vencimiento";
		$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
		$cantidad_bandera=$cantidad;
		/*$bandera=0;
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
					$cantidad_bandera=$cantidad-$cantidad_restante;
				}
			}
		}
		$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
		*/
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
		$sql_inserta_visitadorciclo=mysql_query("insert into salidas_visitador_ciclodetalle values($codigo_salida_visitador,'$cod_material',$cantidad_planificada,$cantidad)");
	}
	else
	{	$sql_inserta_visitadorciclo=mysql_query("insert into salidas_visitador_ciclodetalle values($codigo_salida_visitador,'$cod_material',$cantidad_planificada,$cantidad)");
	}
	
}
$sql_verifica_salida="select * from salidas_visitador_ciclodetalle 
					where codigo_salida_visitador='$codigo_salida_visitador' and cantidad_planificada<>cantidad_sacada";
$resp_verifica_salida=mysql_query($sql_verifica_salida);
$numero_filas=mysql_fetch_array($resp_verifica_salida);
if($numero_filas==0)
{	$actualiza_salida_visitador="update salidas_visitador_ciclo set cod_estado='1' where codigo_salida_visitador='$codigo_salida_visitador'";
	$resp_actualiza_salida=mysql_query($actualiza_salida_visitador);
}					
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_salidaciclosenterosterritorios.php?codigo_ciclo=$codigo_ciclo';
			</script>";
?>