<?php
require("conexion.inc");
require("funciones.php");

$fechaIniBusqueda=$_GET['fechaIniBusqueda'];
$fechaFinBusqueda=$_GET['fechaFinBusqueda'];
$notaIngreso=$_GET['notaIngreso'];
$global_almacen=$_GET['global_almacen'];
$provBusqueda=$_GET['provBusqueda'];

$fechaIniBusqueda=formateaFechaVista($fechaIniBusqueda);
$fechaFinBusqueda=formateaFechaVista($fechaFinBusqueda);
	
//
$consulta = "
    SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, 
	i.nro_correlativo, i.ingreso_anulado
    FROM ingreso_almacenes i, tipos_ingreso ti, ingreso_detalle_almacenes id 
    WHERE i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_ingreso_almacen=id.cod_ingreso_almacen 
    AND i.cod_almacen='$global_almacen'";
if($notaIngreso!="")
   {$consulta = $consulta."AND i.nota_entrega='$notaIngreso' ";
   }
if($fechaIniBusqueda!="--" && $fechaFinBusqueda!="--")
   {$consulta = $consulta."AND '$fechaIniBusqueda'<=i.fecha AND i.fecha<='$fechaFinBusqueda' ";
   }
if($provBusqueda!=""){
	$consulta=$consulta." AND id.cod_material='$provBusqueda' ";
}   
$consulta = $consulta."ORDER BY i.nro_correlativo DESC";

//echo $consulta;
$resp = mysql_query($consulta);
	
echo "<br><center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>Nro. Ingreso</th><th>Nota de Entrega</th><th>Fecha/Hora</th><th>Tipo de Ingreso</th><th>Observaciones</th><th>&nbsp;</th></tr>";
while($dat=mysql_fetch_array($resp)) {
	$codigo=$dat[0];
	$fecha_ingreso=$dat[1];
	$fecha_ingreso_mostrar="$fecha_ingreso[8]$fecha_ingreso[9]-$fecha_ingreso[5]$fecha_ingreso[6]-$fecha_ingreso[0]$fecha_ingreso[1]$fecha_ingreso[2]$fecha_ingreso[3]";
	$hora_ingreso=$dat[2];
	$nombre_tipoingreso=$dat[3];
	$obs_ingreso=$dat[4];
	$nota_entrega=$dat[5];
	$nro_correlativo=$dat[6];
	$anulado=$dat[7];
	echo "<input type='hidden' name='fecha_ingreso$nro_correlativo' value='$fecha_ingreso_mostrar'>";
	$bandera=0;
	$sql_verifica_movimiento="SELECT s.cod_salida_almacenes from salida_almacenes s, salida_detalle_ingreso sdi 
	where s.cod_salida_almacenes=sdi.cod_salida_almacen and s.salida_anulada=0 and sdi.cod_ingreso_almacen='$codigo'";
	$resp_verifica_movimiento=mysql_query($sql_verifica_movimiento);
	$num_filas_movimiento=mysql_num_rows($resp_verifica_movimiento);
	if($num_filas_movimiento!=0) {	
		$color_fondo="#ffff99";
		$chkbox="";
	}
	if($anulado==1) {	
		$color_fondo="#ff8080";
		$chkbox="";
	}
	if($num_filas_movimiento==0 and $anulado==0) {	
		$color_fondo="";
		$chkbox="<input type='checkbox' name='codigo' value='$codigo'>";
	}
//		echo "<tr><td><input type='checkbox' name='codigo' value='$codigo'></td><td align='center'>$fecha_ingreso_mostrar</td><td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td><td>$txt_detalle</td></tr>";
	echo "<tr bgcolor='$color_fondo'><td align='center'>$chkbox&nbsp;</td>
	<td align='center'>$nro_correlativo</td><td align='center'>&nbsp;$nota_entrega</td>
	<td align='center'>$fecha_ingreso_mostrar $hora_ingreso</td>
	<td>$nombre_tipoingreso</td><td>&nbsp;$obs_ingreso</td>
	<td align='center'><a target='_BLANK' href='navegador_detalleingresomuestras.php?codigo_ingreso=$codigo'>
	<img src='imagenes/detalle.png' border='0' title='Ver Detalles del Ingreso' width='40'></a></td></tr>";
}
echo "</table></center><br>";


?>
