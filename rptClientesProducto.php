<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funciones.php");
require("funcion_nombres.php");



$rptCliente=$_GET['rpt_cliente'];
$nombreRptCliente=nombreCliente($rptCliente);
$rptLinea=$_GET['rpt_linea'];
$rptTerritorio=$_GET['rpt_territorio'];
$fechaInicio=$_GET['rpt_fechaini'];
$fechaFinal=$_GET['rpt_fechafin'];
$rptLineaX= str_replace("`","'",$rptLinea);
$rptCanal=$_GET['rpt_canal'];
$rptCanalX= str_replace("`","'",$rptCanal);


$datetime1 = date_create($fechaInicio);
$datetime2 = date_create($fechaFinal);
$interval = date_diff($datetime1, $datetime2);

echo "<h1>Productos que compra un Cliente Individual</h1>";
echo "<h2>Cliente: $nombreRptCliente <br>Linea: $rptLineaX<br>Rango de Fechas: $fechaInicio   $fechaFinal</h2>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>MontoVenta</th><th>CantidadVenta</th><th>Detalle</th><th>Ultima Venta</th><th>Cantidad Ultima Compra</th></tr>";
		
	$sqlProductos="select p.cod_producto, p.nombre_producto, sum(v.monto_venta), sum(v.cantidad) from ventas v, productos p
		where v.cod_ciudad in ($rptTerritorio) and v.cod_producto=p.cod_producto and 
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_cliente in ($rptCliente) 
		and p.linea in ($rptLineaX)
		group by p.cod_producto, p.nombre_producto order by 3 desc";
	$respProductos=mysql_query($sqlProductos);
	
	$indice_tabla=1;
	$porcParetoAcumulado=0;
	$montoTotal=0;
	while($datProductos=mysql_fetch_array($respProductos))
	{
		$codigoProducto=$datProductos[0];
		$nombreProducto=$datProductos[1];
		$montoVentaProducto=$datProductos[2];
		$cantidadVentaProducto=$datProductos[3];
		$montoVentaProductoF=formatonumeroDec($montoVentaProducto);
		$cantidadVentaProductoF=formatonumero($cantidadVentaProducto);
		
		$montoTotal=$montoTotal+$montoVentaProducto;
		
		$sqlUltimaVenta="select DATE_FORMAT(max(v.fecha_venta), '%d/%m/%Y'), v.cantidad from ventas v where v.cod_cliente='$rptCliente' and 
			v.cod_producto='$codigoProducto'";
		$respUltimaVenta=mysql_query($sqlUltimaVenta);
		$fechaUltimaVenta="";
		$cantidadUltimaVenta=0;
		$numRespuesta=mysql_num_rows($respUltimaVenta);
		if($numRespuesta>0){
			$fechaUltimaVenta=mysql_result($respUltimaVenta,0,0);
			$cantidadUltimaVenta=mysql_result($respUltimaVenta,0,1);
		}
		
		echo "<tr><td>$indice_tabla</td><td>$codigoProducto</td><td>$nombreProducto</td>
		<td><span class='textograndenegro'>$montoVentaProductoF</span></td>
		<td><span class='textograndenegro'>$cantidadVentaProductoF</span></td>
		<td><a href='rptProductoIndCliente.php?rpt_cliente=$rpt_cliente&rpt_producto=$codigoProducto&fechaInicio=$fechaInicio&fechaFinal=$fechaFinal'>
			<img src='imagenes/ruteroaprobado.png' width='30' title='Ver detalle del Producto'></a></td>
		<td>$fechaUltimaVenta</td>
		<td align='center'>$cantidadUltimaVenta</td></th>";
		
		$indice_tabla++;
	}
	$montoTotalF=formatonumeroDec($montoTotal);
	echo "<tr><td>-</td><td>-</td><td>TOTALES</td>
	<td><span class='textograndenegro'>$montoTotalF</span></td>
	<td>-</td>
	<td>-</td>
	<td>-</td>
	<td align='center'>-</td></th>";
	echo "</table><br>";
	
	echo "<a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a></center>";
	
?>