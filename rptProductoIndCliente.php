<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funciones.php");
require("funcion_nombres.php");



$rptCliente=$_GET['rpt_cliente'];
$nombreRptCliente=nombreCliente($rptCliente);
$rptProducto=$_GET['rpt_producto'];
$nombreProducto=nombreProducto($rptProducto);
$fechaInicio=$_GET['fechaInicio'];
$fechaFinal=$_GET['fechaFinal'];

echo "<h1>Reporte Ventas de Un Producto x Cliente</h1>";

echo "<h2>Cliente: $nombreRptCliente <br>Producto: $nombreProducto<br>Rango de Fechas: $fechaInicio   $fechaFinal</h2>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Fecha</th><th>Cantidad</th><th>MontoVenta</th></tr>";
		
	$sqlProductos="select p.cod_producto, p.nombre_producto, v.fecha_venta, sum(v.monto_venta), sum(v.cantidad) from ventas v, productos p
		where v.cod_producto=p.cod_producto and 
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_cliente in ($rptCliente) and 
		v.cod_producto in ($rptProducto)
		group by p.cod_producto, p.nombre_producto, v.fecha_venta order by 3 desc";
	$respProductos=mysql_query($sqlProductos);
	
	$indice_tabla=1;
	
	$totalMontoVenta=0;
	$totalCantidadVenta=0;
	
	while($datProductos=mysql_fetch_array($respProductos))
	{
		$codigoProducto=$datProductos[0];
		$nombreProducto=$datProductos[1];
		$fechaVentaX=$datProductos[2];
		
		$montoVentaProducto=$datProductos[3];
		$montoVentaProductoF=formatonumeroDec($montoVentaProducto);
		
		$cantidadVentaProducto=$datProductos[4];
		$cantidadVentaProductoF=formatoNumero($cantidadVentaProducto);
		
		$totalMontoVenta=$totalMontoVenta+$montoVentaProducto;
		$totalCantidadVenta=$totalCantidadVenta+$cantidadVentaProducto;
		
		echo "<tr><td>$indice_tabla</td><td>$fechaVentaX</td>
		<td align='center'><span class='textograndenegro'>$cantidadVentaProductoF</span></td>
		<td align='center'><span class='textograndenegro'>$montoVentaProductoF</span></td>
		</th>";
		
		$indice_tabla++;
	}
	$totalMontoVentaF=formatonumeroDec($totalMontoVenta);
	$totalCantidadVentaF=formatoNumero($totalCantidadVenta);
	
	echo "<tr><td>-</td><td>-</td>
		<td align='center'><span class='textograndenegro'>$totalCantidadVentaF</span></td>
		<td align='center'><span class='textograndenegro'>$totalMontoVentaF</span></td>
		</th>";
	echo "</table><br>";
	
	echo "<table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a></td></tr></table></center>";
	
?>