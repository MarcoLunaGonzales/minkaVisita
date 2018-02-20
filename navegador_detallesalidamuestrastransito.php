<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require('estilos_almacenes_sincab.inc');
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida, s.nro_correlativo
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.almacen_destino='$global_almacen' and s.cod_salida_almacenes='$codigo_salida' and s.grupo_salida=$grupo_salida";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Detalle de Ingreso en Transito</th></tr></table></center><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>Número de Salida (Almacen Origen)</th><th>Fecha</th><th>Tipo de Salida (Almacen Origen)</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$grupo_salida=$dat[4];
	$nro_correlativo=$dat[5];
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$obs_salida</td></tr>";
	echo "</table><br>";
	
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>Muestra</th><th>Cantidad</th></tr>";
	echo "<form method='post' action=''>";
	$sql_detalle="select s.cod_material, s.cantidad_unitaria from salida_detalle_almacenes s 
	where s.cod_salida_almacen='$codigo_salida'";
	$resp_detalle=mysql_query($sql_detalle);
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		if($grupo_salida==2)
		{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		}
		else
		{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
		}
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		if($grupo_salida==1)
		{	$nombre_material="$dat_nombre_material[0] $dat_nombre_material[1]";
		}
		if($grupo_salida==2)
		{	$nombre_material=$dat_nombre_material[0];
		}
		echo "<tr><td>$nombre_material</td><td align='center'>$cantidad_unitaria</td></tr>";
	}
	echo "</table>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>