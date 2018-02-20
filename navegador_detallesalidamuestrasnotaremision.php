<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	require("conexion.inc");
	require('estilos_almacenes_central_sincab.php');
	$sql="select s.cod_salida_almacenes, s.fecha, ts.nombre_tiposalida, s.observaciones, s.grupo_salida,
	s.nro_correlativo, s.territorio_destino, s.almacen_destino
	FROM salida_almacenes s, tipos_salida ts
	where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='$global_almacen' and s.cod_salida_almacenes='$codigo_salida' and s.grupo_salida=1";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Nota de Remisión</th></tr></table></center><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>Número de Salida</th><th>Fecha</th><th>Tipo de Salida</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_salida=$dat[1];
	$fecha_salida_mostrar="$fecha_salida[8]$fecha_salida[9]-$fecha_salida[5]$fecha_salida[6]-$fecha_salida[0]$fecha_salida[1]$fecha_salida[2]$fecha_salida[3]";
	$nombre_tiposalida=$dat[2];
	$obs_salida=$dat[3];
	$grupo_salida=$dat[4];
	$nro_correlativo=$dat[5];
	$territorio_destino=$dat[6];
	$almacen_destino=$dat[7];
		$sql_nombre_territorio="select descripcion from ciudades where cod_ciudad='$territorio_destino'";
		$resp_nombre_territorio=mysql_query($sql_nombre_territorio);
		$dat_nombre_territorio=mysql_fetch_array($resp_nombre_territorio);
		$nombre_territorio=$dat_nombre_territorio[0];
		$sql_nombre_func_destino="select f.paterno, f.materno, f.nombres from funcionarios f, salida_detalle_visitador s
		where f.codigo_funcionario=s.codigo_funcionario and s.cod_salida_almacen='$codigo'";
		$resp_func_destino=mysql_query($sql_nombre_func_destino);
		$dat_func_destino=mysql_fetch_array($resp_func_destino);
		$nombre_func_destino="$dat_func_destino[0] $dat_func_destino[1] $dat_func_destino[2]";
		$sql_nombre_almacen_destino="select nombre_almacen from almacenes where cod_almacen='$almacen_destino'";
		$resp_nombre_almacen=mysql_query($sql_nombre_almacen_destino);
		$dat_nombre_almacen_destino=mysql_fetch_array($resp_nombre_almacen);
		$nombre_almacen_destino=$dat_nombre_almacen_destino[0];
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_salida_mostrar</td><td>$nombre_tiposalida</td><td>&nbsp;$obs_salida</td></tr>";
	echo "<tr><th>Territorio Destino</th><th>Almacen Destino</th><th colspan=2>Funcionario Destino</th></tr>";
	echo "<tr><td>$nombre_territorio</td><td>&nbsp;$nombre_almacen_destino</td><td colspan=2>&nbsp;$nombre_func_destino</td></tr>";
	echo "</table><br>";
	echo "<table border='1' class='texto' cellspacing='0' width='90%' align='center'>";
	echo "<tr><th>&nbsp;</th><th>Muestra</th><th>Cantidad</th></tr>";
	echo "<form method='post' action=''>";
	$sql_detalle="select s.cod_material, s.cantidad_unitaria from salida_detalle_almacenes s 
	where s.cod_salida_almacen='$codigo_salida'";
	$resp_detalle=mysql_query($sql_detalle);
	$indice=1;
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$cantidad_unitaria=$dat_detalle[1];
		$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		$presentacion_material=$dat_nombre_material[1];
		echo "<tr><td align='center'>$indice</td><td width='80%'>$nombre_material $presentacion_material</td><td width='20%' align='center'>$cantidad_unitaria</td></tr>";
		$indice++;
	}
	echo "</table>";
	echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>