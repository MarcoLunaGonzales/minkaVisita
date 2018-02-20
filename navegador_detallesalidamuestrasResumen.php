<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	$codigo_salida=$_GET['codigo_salida'];
	if($global_tipoalmacen==1)
	{	require('estilos_almacenes_central_sincab.php');
	}
	else
	{	require('estilos_almacenes_sincab.inc');
	}
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida, 
	s.nro_correlativo, s.territorio_destino, s.almacen_destino, a.nombre_almacen
	FROM salida_almacenes s, tipos_salida ts, almacenes a
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes in ($codigo_salida) 
	and s.almacen_destino=a.cod_almacen and s.grupo_salida=1";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Nota de Remisión</th></tr></table></center><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	$nro_correlativo="";
	$nombreAlmacenDestino="";
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		//$fecha_salida=$dat[1];
		//$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
		//$nombre_tiposalida=$dat[2];
		//$obs_salida=$dat[3];
		$grupo_salida=$dat[4];
		$nro_correlativo="$nro_correlativo ".$dat[5];
		//$territorio_destino=$dat[6];
		$nombreAlmacenDestino="$nombreAlmacenDestino ".$dat[8];	
	}
	echo "<tr><th>Nro(s) de Salida</th><th>Almacen(es) Destino</th></tr>";
	echo "<tr><td align='center'>$nro_correlativo</td><td>$nombreAlmacenDestino</td></tr>";
	echo "</table><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>&nbsp;</th><th>MM</th><th>Cantidad</th><th>Detalle<table class='texto' width='100%' border='1' cellspacing=0><tr><th width='25%'>Nro. Ingreso</th><th width='25%'>Número Lote</th><th width='25%'>Cantidad Unitaria</th><th width='25%'>Fecha de Vencimiento</th></tr></table></th></tr>";
	echo "<form method='post' action=''>";
	$sql_detalle="select s.cod_material, sum(s.cantidad_unitaria), s.cod_salida_almacen from salida_detalle_almacenes s 
	where s.cod_salida_almacen in ($codigo_salida) group by s.cod_material";
	$resp_detalle=mysql_query($sql_detalle);
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$codigo=$dat_detalle[2];
		$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		$presentacion_material=$dat_nombre_material[1];
		$sql_salida_ingresos="select sdi.cod_ingreso_almacen, sdi.cantidad_unitaria, id.nro_lote, id.fecha_vencimiento, i.nro_correlativo
		from ingreso_almacenes i, salida_detalle_ingreso sdi, ingreso_detalle_almacenes id 
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and sdi.cod_ingreso_almacen=id.cod_ingreso_almacen and sdi.cod_salida_almacen in ($codigo_salida) and sdi.material='$cod_material' and sdi.material=id.cod_material and sdi.nro_lote=id.nro_lote";
		$resp_salida_ingresos=mysql_query($sql_salida_ingresos);
		$detalle_ingreso="<table border='0' class='texto' width='100%'>";
		$detalle_ingreso="$detalle_ingreso";
		while($dat_salida_ingresos=mysql_fetch_array($resp_salida_ingresos))
		{	$codigo_ingreso=$dat_salida_ingresos[0];
			$cantidad_unitaria_ingreso=$dat_salida_ingresos[1];
			$nro_lote_ingreso=$dat_salida_ingresos[2];
			$fecha_vencimiento=$dat_salida_ingresos[3];	
			$correlativo_ingreso=$dat_salida_ingresos[4];
			$detalle_ingreso="$detalle_ingreso<tr><td align='center' width='25%'>$correlativo_ingreso</td><td width='25%' align='center'>$nro_lote_ingreso</td><td align='center' width='25%'>$cantidad_unitaria_ingreso</td><td align='center' width='25%'>$fecha_vencimiento</td></tr>";
		}
		$detalle_ingreso="$detalle_ingreso</table>";
		echo "<tr><td align='center'>$indice</td><td width='40%'>$nombre_material $presentacion_material</td><td width='5%'>$cantidad_unitaria</td><td width='55%'>$detalle_ingreso</td></tr>";
		$indice++;
	}
	echo "</table>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>