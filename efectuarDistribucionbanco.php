<?php
require("conexion.inc");
require("funcion_nombres.php");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}

$global_gestion_distribucion=$_GET['codGestion'];
$global_ciclo_distribucion=$_GET['codCiclo'];

$global_almacen=1000;
$tipo_salida=1000;
$fecha_real=date("Y-m-d");
$hora_salida=date("H:i:s");
$sql_gestion="select nombre_gestion from gestiones where codigo_gestion='$global_gestion_distribucion'";
$resp_gestion=mysql_query($sql_gestion);
$dat_gestion=mysql_fetch_array($resp_gestion);
$nombre_gestion=$dat_gestion[0];


$observaciones="SALIDA AUTOMATICA Banco Muestras Ciclo: $global_ciclo_distribucion Gesti&oacute;n: $nombre_gestion ";
//aqui inserta la parte de MM

$grupo_salida=1;
$sql_visitador="select distinct(f.`codigo_funcionario`), f.`cod_ciudad` from `distribucion_banco_muestras` d, funcionarios f 
	where f.`codigo_funcionario`=d.`cod_visitador` 
	and d.`codigo_gestion`='$global_gestion_distribucion' and d.`cod_ciclo`='$global_ciclo_distribucion'";

echo $sql_visitador."<br>";
				
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

	$sql_linea="select territorio, cod_visitador, codigo_producto, sum(cantidad_planificada), 
			sum(cantidad_distribuida), sum(cantidad_sacadaalmacen), grupo_salida
			from distribucion_banco_muestras
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
			and cod_visitador='$codvisitador' and cantidad_distribuida>cantidad_sacadaalmacen
			and grupo_salida='$grupo_salida' group by territorio, cod_visitador, codigo_producto";
	
	echo "SQL PRODUCTOS:  ".$sql_linea."<BR><BR>";
	
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
						
						echo $upd_cantidades."<br><br>";

						$resp_upd_cantidades=mysql_query($upd_cantidades);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					}
				}
			}
			$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
			
		}
	}
}
//TERMINA SACAR MUESTRAS MEDICAS

//aqui inserta la parte de MATERIAL DE APOYO
$grupo_salida=2;
$sql_visitador="select distinct(f.`codigo_funcionario`), f.`cod_ciudad` from `distribucion_banco_muestras` d, funcionarios f 
	where f.`codigo_funcionario`=d.`cod_visitador` 
	and d.`codigo_gestion`='$global_gestion_distribucion' and d.`cod_ciclo`='$global_ciclo_distribucion'";
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

	
	$sql_linea="select territorio, cod_visitador, codigo_producto, sum(cantidad_planificada), 
		sum(cantidad_distribuida), sum(cantidad_sacadaalmacen), grupo_salida
		from distribucion_banco_muestras
		where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
		and cod_visitador='$codvisitador' and cantidad_distribuida>cantidad_sacadaalmacen
		and grupo_salida='$grupo_salida' group by territorio, cod_visitador, codigo_producto";
		
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
						echo $upd_cantidades."<br><br>";
						$resp_upd_cantidades=mysql_query($upd_cantidades);
						$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					}
				}
			}
			$sql_inserta2=mysql_query("insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')");
			
			}
	}
}
//TERMINA SACAR MATERIAL DE APOYO

//ACTUALIZAMOS LA TABLA DE DISTRIBUCIONES
$sqlActDistribucion="update distribucion_banco_muestras set cantidad_sacadaalmacen=cantidad_distribuida
			where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'";
$respActDistribucion=mysql_query($sqlActDistribucion);

/*echo "<script language='JavaScript'>
		alert('Las salidas se efectuaron correctamente.');
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$codigo_linea';
</script>";*/


?>