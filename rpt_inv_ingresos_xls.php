<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=archivo.xls");
require('estilos_reportes_almacencentral.php');
require('conexion.inc');
require('function_formatofecha.php');
$fecha_reporte=date("d/m/Y");
$txt_reporte="Fecha de Reporte <strong>$fecha_reporte</strong>";
$sql_tipo_ingreso="select nombre_tipoingreso from tipos_ingreso where cod_tipoingreso='$tipo_ingreso'";
$resp_tipo_ingreso=mysql_query($sql_tipo_ingreso);
$datos_tipo_ingreso=mysql_fetch_array($resp_tipo_ingreso);
$nombre_tipoingreso=$datos_tipo_ingreso[0];
if($tipo_ingreso!="")
{	$nombre_tipoingresomostrar="Tipo de Ingreso: <strong>$nombre_tipoingreso</strong>";
}
else
{	$nombre_tipoingresomostrar="Todos los tipos de Ingreso";
}
	if($tipo_item==1)
	{$nombre_item="Muestra M�dica";}else{$nombre_item="Material de Apoyo";}
	echo "<table align='center' class='textotit'><tr><td align='center'>Reporte Ingresos Almacen<br>$nombre_tipoingresomostrar Fecha inicio: <strong>$fecha_ini</strong> Fecha final: <strong>$fecha_fin</strong> Tipo de Item: <strong>$nombre_item</strong><br>$txt_reporte</th></tr></table>";

	//desde esta parte viene el reporte en si
	$fecha_iniconsulta=cambia_formatofecha($fecha_ini);
	$fecha_finconsulta=cambia_formatofecha($fecha_fin);
	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
	FROM ingreso_almacenes i, tipos_ingreso ti
	where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$rpt_almacen' and i.grupo_ingreso=$tipo_item and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' and i.cod_tipoingreso='$tipo_ingreso' order by i.nro_correlativo";
	if($tipo_ingreso=='')
	{	$sql="select i.cod_ingreso_almacen, i.fecha, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='$rpt_almacen' and i.grupo_ingreso=$tipo_item and i.fecha>='$fecha_iniconsulta' and i.fecha<='$fecha_finconsulta' order by i.nro_correlativo";
	}
	$resp=mysql_query($sql);
	echo "<center><br><table border='1' class='texto' cellspacing='0' width='100%'>";
	echo "<tr class='textomini'><th>Nro.</th><th>Nota de Entrega</th><th>Fecha</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>Estado</th><th>&nbsp;</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$codigo=$dat[0];
		$fecha_ingreso=$dat[1];
		$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
		$nombre_tipoingreso=$dat[2];
		$obs_ingreso=$dat[3];
		$nota_entrega=$dat[4];
		$nro_correlativo=$dat[5];
		$anulado=$dat[6];
		echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
		$bandera=0;
		$sql_verifica_movimiento="select s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi
		where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
		$resp_verifica_movimiento=mysql_query($sql_verifica_movimiento);
		$num_filas_movimiento=mysql_num_rows($resp_verifica_movimiento);
		if($num_filas_movimiento!=0)
		{	$estado_ingreso="Con Movimiento";
		}
		if($anulado==1)
		{	$estado_ingreso="Anulado";
		}
		if($num_filas_movimiento==0 and $anulado==0)
		{	$estado_ingreso="Sin Movimiento";
		}
		//desde esta parte sacamos el detalle del ingreso
		$sql_detalle="select i.cod_material, i.nro_lote,i.fecha_vencimiento, i.cantidad_unitaria from ingreso_detalle_almacenes i
		where i.cod_ingreso_almacen='$codigo'";
		$resp_detalle=mysql_query($sql_detalle);
		$bandera=0;
		$detalle_ingreso="";
		$detalle_ingreso.="<table border=1 cellspacing='0' align='center' class='textomini' width='100%'>";
		$detalle_ingreso.="<tr><th width='60%'>Material</th><th width='10%'>N�mero de Lote</th><th width='20%'>Fecha Vencimiento</th><th width='10%'>Cantidad</th></tr>";
		while($dat_detalle=mysql_fetch_array($resp_detalle))
		{	$cod_material=$dat_detalle[0];
			//verificamos si el productos pertenece a la linea
			if($rpt_linea!=0)
			{	$sql_linea="select * from producto_especialidad where codigo_mm='$cod_material' and codigo_linea='$rpt_linea'";
				$resp_linea=mysql_query($sql_linea);
				$num_filas=mysql_num_rows($resp_linea);
				if($num_filas!=0)
				{	$bandera=1;
				}
			}

			$numero_lote=$dat_detalle[1];
			$fecha_vencimiento=$dat_detalle[2];
			$fecha_vencimiento_mostrar="$fecha_vencimiento[8]$fecha_vencimiento[9]-$fecha_vencimiento[5]$fecha_vencimiento[6]-$fecha_vencimiento[0]$fecha_vencimiento[1]$fecha_vencimiento[2]$fecha_vencimiento[3]";
			$cantidad_unitaria=$dat_detalle[3];
			if($tipo_item==1)
			{	$sql_nombre_material="select descripcion, presentacion from muestras_medicas where codigo='$cod_material'";
			}
			else
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			}
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$dat_nombre_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_nombre_material[0];
			$presentacion=$dat_nombre_material[1];
			if($rpt_linea!=0 and $num_filas!=0)
			{	$detalle_ingreso.="<tr><td>$nombre_material $presentacion</td><td align='center'>$numero_lote</td><td align='center'>$fecha_vencimiento_mostrar</td><td align='center'>$cantidad_unitaria</td></tr>";
			}
			if($rpt_linea==0)
			{	$detalle_ingreso.="<tr><td>$nombre_material $presentacion</td><td align='center'>$numero_lote</td><td align='center'>$fecha_vencimiento_mostrar</td><td align='center'>$cantidad_unitaria</td></tr>";
			}
		}
		$detalle_ingreso.="</table>";
		if($rpt_linea==0)
		{	echo "<tr bgcolor='$color_fondo'><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>&nbsp;$estado_ingreso</td><td align='center'>$detalle_ingreso</td></tr>";
		}
		if($rpt_linea!=0 and $bandera==1)
		{	echo "<tr bgcolor='$color_fondo'><td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>&nbsp;$estado_ingreso</td><td align='center'>$detalle_ingreso</td></tr>";
		}
	}
	echo "</table></center><br>";
?>