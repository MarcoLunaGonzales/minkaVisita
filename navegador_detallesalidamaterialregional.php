<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida' and s.grupo_salida=1";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Detalle de Salida de Material</th></tr></table></center><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>Número de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$grupo_salida=$dat[4];
	echo "<tr><td align='center'>$codigo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>$obs_salida</td></tr>";
	echo "</table><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Muestra</th><th>Cantidad</th><th>Detalle</th></tr>";
	echo "<form method='post' action=''>";
	$sql_detalle="select s.cod_material, s.cantidad_unitaria from salida_detalle_almacenes s 
	where s.cod_salida_almacen='$codigo_salida'";
	$resp_detalle=mysql_query($sql_detalle);
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$sql_nombre_material="select descripcion_material from material_apoyo where codigo='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		$sql_salida_ingresos="select sdi.cod_ingreso_almacen, sdi.cantidad_unitaria, id.nro_lote, id.fecha_vencimiento
		from salida_detalle_ingreso sdi, ingreso_detalle_almacenes id 
		where sdi.cod_ingreso_almacen=id.cod_ingreso_almacen and sdi.cod_salida_almacen='$codigo' and sdi.material='$cod_material' and sdi.material=id.cod_material";
		$resp_salida_ingresos=mysql_query($sql_salida_ingresos);
		$detalle_ingreso="<table border='1' class='texto' width='100%'>";
		$detalle_ingreso="$detalle_ingreso<tr><th>Código Ingreso</th><th>Número Lote</th><th>Cantidad Unitaria</th><th>Fecha de Vencimiento</th></tr>";
		while($dat_salida_ingresos=mysql_fetch_array($resp_salida_ingresos))
		{	$codigo_ingreso=$dat_salida_ingresos[0];
			$cantidad_unitaria_ingreso=$dat_salida_ingresos[1];
			$nro_lote_ingreso=$dat_salida_ingresos[2];
			$fecha_vencimiento=$dat_salida_ingresos[3];	
			$detalle_ingreso="$detalle_ingreso<tr><td align='center'>$codigo_ingreso</td><td>$nro_lote_ingreso</td><td align='center'>$cantidad_unitaria_ingreso</td><td align='center'>$fecha_vencimiento</td></tr>";
		}
		$detalle_ingreso="$detalle_ingreso</table>";
		echo "<tr><td align='center'>$indice</td><td width='40%'>$nombre_material</td><td width='5%'>$cantidad_unitaria</td><td width='55%'>$detalle_ingreso</td></tr>";
		$indice++;
	}
	echo "</table>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>