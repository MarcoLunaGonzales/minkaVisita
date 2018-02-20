<?php
require("conexion.inc");
require("funcion_nombres.php");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}

$global_almacen=1000;
$tipo_salida=1000;
$fecha_real=date("Y-m-d");
$hora_salida=date("H:i:s");
$sql_gestion="select nombre_gestion from gestiones where codigo_gestion='$global_gestion_distribucion'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$nombre_gestion=$dat_gestion[0];
$nombreLinea=nombreLinea($codigo_linea);

$observaciones="SALIDA AUTOMATICA Ciclo: $global_ciclo_distribucion Gestión: $nombre_gestion Linea: $nombreLinea";
//aqui inserta la parte de MM

$grupo_salida=1;
$sql_visitador="select f.codigo_funcionario, f.cod_ciudad from funcionarios f, funcionarios_lineas fl
				where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$codigo_linea' and f.cod_cargo='1011' 
				order by f.cod_ciudad";
$resp_visitador=mysql_query($sql_visitador);
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codvisitador=$dat_visitador[0];
	$territorio=$dat_visitador[1];
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
	echo $nro_correlativo;
	$sql_almacen_destino="select cod_almacen from almacenes where cod_ciudad='$territorio'";
	$resp_almacen_destino=mysql_query($sql_almacen_destino);
	$dat_almacen_destino=mysql_fetch_array($resp_almacen_destino);
	$almacen=$dat_almacen_destino[0];

	$sql_linea="select territorio, cod_visitador, codigo_producto, cantidad_planificada, cantidad_distribuida, cantidad_sacadaalmacen, grupo_salida
			from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and cantidad_distribuida>cantidad_sacadaalmacen
			and grupo_salida='$grupo_salida'";
	//echo $sql_linea;
	$resp_linea=mysql_query($sql_linea);
	$filas_a_insertar=mysql_num_rows($resp_linea);
	//echo $filas_a_insertar."<br>";
	if($filas_a_insertar > 0)
	{	$sql_inserta=mysql_query("insert into salida_almacenes values('$codigo','$global_almacen','$tipo_salida','$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,'$grupo_salida','','',0,0,0,0,'','$nro_correlativo',0)");
		$sql_inserta=mysql_query("insert into salida_detalle_visitador values('$codigo','$codvisitador','$global_gestion_distribucion','$global_ciclo_distribucion')");
	}
	while($dat_linea=mysql_fetch_array($resp_linea))
	{	$territorio=$dat_linea[0];
		$codigoproducto=$dat_linea[2];
		$cantidadplanificada=$dat_linea[3];
		$cantidaddistribuida=$dat_linea[4];
		$cantidadsacadaalmacen=$dat_linea[5];
		//$grupo_salida=$dat_linea[6];
		$cantidad_a_sacarefectiva=$cantidaddistribuida-$cantidadsacadaalmacen;
		//echo "$territorio $codvisitador $codigoproducto $cantidadplanificada $cantidaddistribuida $cantidadsacadaalmacen<br>";

		//desde aqui probamos la salida de almacenes

		$cod_material=$codigoproducto;
		$cantidad=$cantidad_a_sacarefectiva;
		$cantidad_planificada=$vector_cantidadesplanificadas[$i];
		if($cantidad!=0)
		{	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
			ingreso_almacenes i
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and id.cod_material='$cod_material' 
			and id.cantidad_restante> 0 and i.`ingreso_anulado`=0 order by id.fecha_vencimiento";
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
					{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupo_salida')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
					}
					else
					{
						$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_salida')";
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
			//aqui actualizamos la tabla de distribuciones
			$sql_cantsacada="select cantidad_sacadaalmacen distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
				and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and codigo_producto='$cod_material'";
			$resp_cantsacada=mysql_query($sql_cantsacada);
			$dat_cantsacada=mysql_fetch_array($resp_cantsacada);
			$cantidad_sacada_almacen=$dat_cantsacada[0];	
			$cantidad_a_actualizar=$cantidad_sacada_almacen+$cantidad;
			$sql_actualizadistribucion="update distribucion_productos_visitadores set cantidad_sacadaalmacen=cantidad_sacadaalmacen+'$cantidad_a_actualizar'
				where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
				and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and codigo_producto='$cod_material'";
			$resp_actualizadistribucion=mysql_query($sql_actualizadistribucion);
			//fin prueba salida almacenes
		}
	}
}
//TERMINA SACAR MUESTRAS MEDICAS

//aqui inserta la parte de MATERIAL DE APOYO
$grupo_salida=2;
$sql_visitador="select f.codigo_funcionario, f.cod_ciudad from funcionarios f, funcionarios_lineas fl
				where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$codigo_linea' and f.cod_cargo='1011' and f.estado=1 order by f.cod_ciudad";
$resp_visitador=mysql_query($sql_visitador);
while($dat_visitador=mysql_fetch_array($resp_visitador))
{	$codvisitador=$dat_visitador[0];
	$territorio=$dat_visitador[1];
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
	echo $nro_correlativo;
	$sql_almacen_destino="select cod_almacen from almacenes where cod_ciudad='$territorio'";
	$resp_almacen_destino=mysql_query($sql_almacen_destino);
	$dat_almacen_destino=mysql_fetch_array($resp_almacen_destino);
	$almacen=$dat_almacen_destino[0];

	$sql_linea="select territorio, cod_visitador, codigo_producto, cantidad_planificada, cantidad_distribuida, cantidad_sacadaalmacen, grupo_salida
			from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and cantidad_distribuida>cantidad_sacadaalmacen
			and grupo_salida='$grupo_salida'";
	//echo $sql_linea;
	$resp_linea=mysql_query($sql_linea);
	$filas_a_insertar=mysql_num_rows($resp_linea);
	//echo $filas_a_insertar."<br>";
	if($filas_a_insertar > 0)
	{	$sql_inserta=mysql_query("insert into salida_almacenes values('$codigo','$global_almacen','$tipo_salida','$fecha_real','$hora_salida','$territorio','$almacen','$observaciones',0,'$grupo_salida','','',0,0,0,0,'','$nro_correlativo',0)");
		$sql_inserta=mysql_query("insert into salida_detalle_visitador values('$codigo','$codvisitador','$global_gestion_distribucion','$global_ciclo_distribucion')");
	}
	while($dat_linea=mysql_fetch_array($resp_linea))
	{	$territorio=$dat_linea[0];
		$codigoproducto=$dat_linea[2];
		$cantidadplanificada=$dat_linea[3];
		$cantidaddistribuida=$dat_linea[4];
		$cantidadsacadaalmacen=$dat_linea[5];
		//$grupo_salida=$dat_linea[6];
		$cantidad_a_sacarefectiva=$cantidaddistribuida-$cantidadsacadaalmacen;
		//echo "$territorio $codvisitador $codigoproducto $cantidadplanificada $cantidaddistribuida $cantidadsacadaalmacen<br>";

		//desde aqui probamos la salida de almacenes

		$cod_material=$codigoproducto;
		$cantidad=$cantidad_a_sacarefectiva;
		$cantidad_planificada=$vector_cantidadesplanificadas[$i];
		if($cantidad!=0)
		{	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
			ingreso_almacenes i
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and id.cod_material='$cod_material' 
			and id.cantidad_restante> 0 and i.`ingreso_anulado`=0 order by id.fecha_vencimiento";
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
					{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupo_salida')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
					}
					else
					{
						$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_salida')";
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
			//aqui actualizamos la tabla de distribuciones
			$sql_cantsacada="select cantidad_sacadaalmacen distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and codigo_producto='$cod_material'";
			$resp_cantsacada=mysql_query($sql_cantsacada);
			$dat_cantsacada=mysql_fetch_array($resp_cantsacada);
			$cantidad_sacada_almacen=$dat_cantsacada[0];	
			$cantidad_a_actualizar=$cantidad_sacada_almacen+$cantidad;
			$sql_actualizadistribucion="update distribucion_productos_visitadores set cantidad_sacadaalmacen=cantidad_sacadaalmacen+'$cantidad_a_actualizar'
				where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
				and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and codigo_producto='$cod_material'";
			$resp_actualizadistribucion=mysql_query($sql_actualizadistribucion);
			//fin prueba salida almacenes
		}
	}
}
//TERMINA SACAR MATERIAL DE APOYO

/*//PROBAMOS SACAR POR REGIONAL LA DISTRIBUCION

$tipo_salida=1009;
$grupo_salida=2;
$sql_regional="select cod_ciudad, descripcion from ciudades order by descripcion";
$resp_regional=mysql_query($sql_regional);
while($dat_regional=mysql_fetch_array($resp_regional))
{	$codciudad=$dat_regional[0];
	$nombre_ciudad=$dat_regional[1];
	echo $nombre_ciudad."<br>";
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
	echo $nro_correlativo;
	$sql_almacen_destino="select cod_almacen from almacenes where cod_ciudad='$codciudad";
	$resp_almacen_destino=mysql_query($sql_almacen_destino);
	$dat_almacen_destino=mysql_fetch_array($resp_almacen_destino);
	$almacen=$dat_almacen_destino[0];
	/*$sql_linea="select territorio, cod_visitador, codigo_producto, cantidad_planificada, cantidad_distribuida, cantidad_sacadaalmacen, grupo_salida
			from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and codigo_linea='$codigo_linea' and territorio='$codciudad' and cantidad_distribuida>cantidad_sacadaalmacen
			and grupo_salida='$grupo_salida'";*/
/*)	$sql_linea="select territorio, codigo_producto, sum(cantidad_planificada)
			from distribucion_productos_visitadores
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and territorio='$codciudad' and cantidad_distribuida>cantidad_sacadaalmacen
			and grupo_salida='2' group by territorio, codigo_producto";		
	//echo $sql_linea;
	$resp_linea=mysql_query($sql_linea);
	$filas_a_insertar=mysql_num_rows($resp_linea);
	//echo $filas_a_insertar."<br>";
	if($filas_a_insertar > 0)
	{	$sql_inserta=mysql_query("insert into salida_almacenes values('$codigo','$global_almacen','$tipo_salida','$fecha_real','$hora_salida','$codciudad','$almacen','$observaciones',0,'$grupo_salida','','',0,0,0,0,'','$nro_correlativo',0)");
	}
	while($dat_linea=mysql_fetch_array($resp_linea))
	{	$territorio=$dat_linea[0];
		$codigoproducto=$dat_linea[2];
		$cantidadplanificada=$dat_linea[3];
		//$cantidaddistribuida=$dat_linea[4];
		//$cantidadsacadaalmacen=$dat_linea[5];
		$cantidad_a_sacarefectiva=$cantidaddistribuida-$cantidadsacadaalmacen;
		//desde aqui probamos la salida de almacenes
		$cod_material=$codigoproducto;
		$cantidad=$cantidad_a_sacarefectiva;
		$cantidad_planificada=$vector_cantidadesplanificadas[$i];
		if($cantidad!=0)
		{	$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
			ingreso_almacenes i
			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and id.cod_material='$cod_material' and id.cantidad_restante<>0 order by id.fecha_vencimiento";
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
					{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','$grupo_salida')";
						$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
						$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
					}
					else
					{
						$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_salida')";
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
			//aqui actualizamos la tabla de distribuciones
			/*$sql_cantsacada="select cantidad_sacadaalmacen distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and codigo_linea='$codigo_linea' and territorio= and codigo_producto='$cod_material'";
			$resp_cantsacada=mysql_query($sql_cantsacada);
			$dat_cantsacada=mysql_fetch_array($resp_cantsacada);
			$cantidad_sacada_almacen=$dat_cantsacada[0];	
			$cantidad_a_actualizar=$cantidad_sacada_almacen+$cantidad;
			$sql_actualizadistribucion="update distribucion_productos_visitadores set cantidad_sacadaalmacen='$cantidad_a_actualizar'
				where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
				and codigo_linea='$codigo_linea' and cod_visitador='$codvisitador' and codigo_producto='$cod_material'";
			$resp_actualizadistribucion=mysql_query($sql_actualizadistribucion);
			//fin prueba salida almacenes*/
/*		}
	}
}*/
//FIN SACAR POR REGIONAL

echo "<script language='JavaScript'>
		alert('Las salidas se efectuaron correctamente.');
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$codigo_linea';
</script>";


?>