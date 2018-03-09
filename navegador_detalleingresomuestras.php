<?php
	require("conexion.inc");
	if($global_tipoalmacen==1)
	{	require('estilos_almacenes_central_sincab.php');
	}
	else
	{	require('estilos_almacenes_sincab.inc');
	}
	$grupoIngreso=$_GET["grupoIngreso"];
	
	echo "<form method='post' action=''>";
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.grupo_ingreso, i.nro_correlativo
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$global_almacen' and i.cod_ingreso_almacen='$codigo_ingreso' and i.grupo_ingreso=$grupoIngreso";
	$resp=mysql_query($sql);
	
	if($grupoIngreso==1){
		echo "<h1>Detalle de Ingreso de Muestras</h1>";
	}else{
		echo "<h1>Detalle de Ingreso de Material</h1>";
	}

	
	echo "<center><table class='texto'>";
	echo "<tr><th>N&uacute;mero de Ingreso</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th></tr>";
	$dat=mysql_fetch_array($resp);
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$nombre_tipoingreso=$dat[2];
	$obs_ingreso=$dat[3];
	$grupo_ingreso=$dat[4];
	$nro_correlativo=$dat[5];
	echo "<tr><td align='center'>$nro_correlativo</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td></tr>";
	echo "</table>";
	if($grupo_ingreso==1){	
		$sql_detalle="select i.cod_material, i.nro_lote, i.fecha_vencimiento, i.cantidad_unitaria 
		from ingreso_detalle_almacenes i, `muestras_medicas` m 
		where i.`cod_material`=m.`codigo` and i.cod_ingreso_almacen = '$codigo' order by m.`descripcion`";
	
	}else{
		$sql_detalle="select i.cod_material, i.nro_lote, i.fecha_vencimiento, i.cantidad_unitaria 
		from ingreso_detalle_almacenes i, `material_apoyo` m 
		where i.`cod_material`=m.`codigo_material` and i.cod_ingreso_almacen = '$codigo' order by m.`descripcion_material`";
	}
	
	$resp_detalle=mysql_query($sql_detalle);
	//echo "<br>$sql_detalle";
	echo "<br><table class='texto'>";
	echo "<tr><th>Muestra/Material</th><th>N&uacute;mero de Lote</th><th>Fecha Vencimiento</th><th>Cantidad</th></tr>";
	while($dat_detalle=mysql_fetch_array($resp_detalle))
	{	$cod_material=$dat_detalle[0];
		$numero_lote=$dat_detalle[1];
		$fecha_vencimiento=$dat_detalle[2];
		$fecha_vencimiento_mostrar="$fecha_vencimiento[8]$fecha_vencimiento[9]-$fecha_vencimiento[5]$fecha_vencimiento[6]-$fecha_vencimiento[0]$fecha_vencimiento[1]$fecha_vencimiento[2]$fecha_vencimiento[3]";
		$cantidad_unitaria=$dat_detalle[3];
		if($grupo_ingreso==1)
		{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
		}
		else
		{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
		}
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_nombre_material[0];
		$presentacion=$dat_nombre_material[1];
		echo "<tr><td>$nombre_material $presentacion</td><td align='center'>$numero_lote</td><td align='center'>$fecha_vencimiento_mostrar</td><td align='center'>$cantidad_unitaria</td></tr>";
	}
	echo "</table>";
	
	echo "<br><center><a href='javascript:window.print();'><img border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'/></a>";
//	echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	
?>