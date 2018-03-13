<?php
require("conexion.inc");
$global_almacen=$_COOKIE['global_almacen'];
require("estilos_almacenes.inc");
require('saca_nombre_muestra.php');

//EL TIPO DE INGRESO POR DEFECTO ES EL DE TRASPASOS
$tipo_ingreso=1002;

$sql="select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
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
$grupoIngreso=$_GET['grupoIngreso'];

$sql="select max(nro_correlativo)+1 from ingreso_almacenes 
	where cod_almacen='$global_almacen' and grupo_ingreso='$grupoIngreso'";
$resp=mysql_query($sql);
$nro_correlativo=mysql_result($resp,0,0);


$hora_sistema=date("H:m:s");
//$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$fecha_real=$fecha;

$vector_material=explode(",",$vector_material);
$vector_cantidad_parcial=explode(",",$vector_cantidad_parcial);
$vector_chk=explode(",",$vector_chk);
$vector_tipoobs=explode(",",$vector_tipoobs);
$vector_obs=explode(",",$vector_obs);
$vector_cantidades=explode(",",$vector_cantidades);

$txtInserta="insert into ingreso_almacenes (cod_ingreso_almacen, cod_almacen, cod_tipoingreso, fecha, hora_ingreso, observaciones,
grupo_ingreso, cod_salida_almacen, nota_entrega, nro_correlativo, ingreso_anulado, cod_orden_compra, nro_empaques) 
values($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema', '$observaciones', $grupoIngreso, $codigo_salida, 
'$nota_ingreso','$nro_correlativo',0,0,0)";
$sql_inserta=mysql_query($txtInserta);

if($sql_inserta==1){
	//INSERTA EL DETALLE DEL INGRESO
	for($i=0;$i<=$cantidad_material-1;$i++)
	{	$cod_material=$vector_material[$i];
		$cantidad_parcial=$vector_cantidad_parcial[$i];
		$chk=$vector_chk[$i];
		$tipo_observacion=$vector_tipoobs[$i];
		$obs=$vector_obs[$i];
		$cantidad=$vector_cantidades[$i];
		
		$sql_lote_fechavencimiento="select id.nro_lote, id.fecha_vencimiento
			from ingreso_detalle_almacenes id, salida_almacenes s, salida_detalle_ingreso sdi
			where sdi.cod_salida_almacen=s.cod_salida_almacenes and sdi.material=id.cod_material and
			sdi.cod_ingreso_almacen=id.cod_ingreso_almacen and sdi.cod_salida_almacen='$codigo_salida' and id.cod_material='$cod_material';";
		$resp_lote_fechavencimiento=mysql_query($sql_lote_fechavencimiento);
		$dat_lote_fechavencimiento=mysql_fetch_array($resp_lote_fechavencimiento);
		$numero_lote=$dat_lote_fechavencimiento[0];
		$fecha_vencimiento=$dat_lote_fechavencimiento[1];
		$txtDetalle="insert into ingreso_detalle_almacenes(cod_ingreso_almacen, cod_material, nro_lote, fecha_vencimiento, 
		cantidad_unitaria, cantidad_restante, nro_empaques) 
		values($codigo,'$cod_material','$numero_lote','$fecha_vencimiento','$cantidad_parcial','$cantidad_parcial',0)";
		$sql_inserta2=mysql_query($txtDetalle);
		$sql_actualiza_estado=mysql_query("update salida_almacenes set estado_salida='2' where cod_salida_almacenes='$codigo_salida'");
	}
	//FIN DETALLE INGRESO
	
	//AQUI SE VERIFICA EL INGRESO PARA REALIZAR LA SALIDA AUTOMATICA O NO	
	/*$sql_visitador="select * from salida_detalle_visitador where cod_salida_almacen='$codigo_salida'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$codigo_funcionario=$dat_visitador[1];

	if($codigo_funcionario!=0){	
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
		
		
		
		$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='$grupo_ingreso' order by cod_salida_almacenes desc";
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
		$fecha=date("d/m/Y");
		$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
		$hora_salida=date("H:i:s");

		
		$tipo_salida=1001;
		$observaciones="Salida automatica para visita.";
		$sql_inserta="insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$global_agencia','$global_almacen','$observaciones',0,$grupo_ingreso,'','',0,0,0,0,'','$nro_correlativo',0)";
		$resp_inserta=mysql_query($sql_inserta);
		$sql_inserta=mysql_query("insert into salida_detalle_visitador values($codigo,$codigo_funcionario,0,0)");
		for($i=0;$i<=$cantidad_material-1;$i++)
		{	$cod_material=$vector_material[$i];
			$fecha_vencimiento=$vector_fechavenci[$i];
			$fecha_sistema_vencimiento=$fecha_vencimiento[6].$fecha_vencimiento[7].$fecha_vencimiento[8].$fecha_vencimiento[9]."-".$fecha_vencimiento[3].$fecha_vencimiento[4]."-".$fecha_vencimiento[0].$fecha_vencimiento[1];
			$cantidad=$vector_cantidades[$i];
			$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
			ingreso_almacenes i
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and i.ingreso_anulado=0
			and id.cod_material='$cod_material' and id.cantidad_restante<>0 order by id.cod_ingreso_almacen";
			$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
			$cantidad_bandera=$cantidad;
			$bandera=0;
			while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
			{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
				$cantidad_restante=$dat_detalle_ingreso[1];
				$nro_lote=$dat_detalle_ingreso[2];
				if($bandera!=1)
				{	if($cantidad_bandera>$cantidad_restante)
					{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupo_ingreso')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
					}
					else
					{
						$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_ingreso')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
						$bandera=1;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					}
				}
			}
			$sql_inserta2="insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')";
			$resp_inserta2=mysql_query($sql_inserta2);
			//echo $sql_inserta2;
		}
	}*/
	//FIN SACAR SALIDA AUTOMATICA
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomuestras.php?grupoIngreso=$grupoIngreso';
			</script>";	
}else{
	echo "<script language='Javascript'>
		alert('Tuvimos un problema para guardar los datos consulte con el administrador!.');
		location.href='navegador_ingresomuestras.php?grupoIngreso=$grupoIngreso';
		</script>";
}

?>