<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funciones.php");
require("funcion_nombres.php");



$rptProducto=$_GET['rpt_producto'];
$nombreRptProducto=nombreProducto($rptProducto);
$rptLinea=$_GET['rpt_linea'];
$rptTerritorio=$_GET['rpt_territorio'];
$nombreTerritorio=nombreTerritorio($rptTerritorio);
$fechaInicio=$_GET['rpt_fechaini'];
$fechaFinal=$_GET['rpt_fechafin'];
$rptCanal=$_GET['rpt_canal'];

$rptLineaX= str_replace("`","'",$rptLinea);
$rptCanalX= str_replace("`","'",$rptCanal);


$datetime1 = date_create($fechaInicio);
$datetime2 = date_create($fechaFinal);
$interval = date_diff($datetime1, $datetime2);

echo "<h1>Clientes que compran un Producto Individual</h1>";
echo "<h2>Territorio: $nombreTerritorio <br> Canal: $rptCanalX<br>Producto: $nombreRptProducto <br>
Rango de Fechas: $fechaInicio   $fechaFinal</h2>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Cliente</th><th>MontoVenta</th><th>CantidadVenta</th><th>Detalle</th><th>Ultima Venta</th><th>Cantidad Ultima Compra</th></tr>";
		
	$sqlProductos="select p.cod_cliente, p.nombre_cliente, sum(v.monto_venta), sum(v.cantidad) from ventas v, clientes p
		where v.cod_ciudad in ($rptTerritorio) and v.cod_cliente=p.cod_cliente and 
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_producto in ($rptProducto) 
		and v.canal in ($rptCanalX)
		group by p.cod_cliente, p.nombre_cliente order by 3 desc";
	$respProductos=mysql_query($sqlProductos);
	
	$indice_tabla=1;
	$porcParetoAcumulado=0;
	$montoTotal=0;
	while($datProductos=mysql_fetch_array($respProductos))
	{
		$codigoCliente=$datProductos[0];
		$nombreCliente=$datProductos[1];
		$montoVentaProducto=$datProductos[2];
		$cantidadVentaProducto=$datProductos[3];
		
		$montoVentaProductoF=formatonumeroDec($montoVentaProducto);
		$cantidadVentaProductoF=formatonumero($cantidadVentaProducto);
		
		$montoTotal=$montoTotal+$montoVentaProducto;
		
		$sqlUltimaVenta="select DATE_FORMAT(max(v.fecha_venta), '%d/%m/%Y'), v.cantidad from ventas v where v.cod_cliente='$codigoCliente' and 
			v.cod_producto='$rptProducto'";
		$respUltimaVenta=mysql_query($sqlUltimaVenta);
		$fechaUltimaVenta="";
		$cantidadUltimaVenta=0;
		$numRespuesta=mysql_num_rows($respUltimaVenta);
		if($numRespuesta>0){
			$fechaUltimaVenta=mysql_result($respUltimaVenta,0,0);
			$cantidadUltimaVenta=mysql_result($respUltimaVenta,0,1);
		}
		
		echo "<tr><td>$indice_tabla</td><td>$codigoCliente</td><td>$nombreCliente</td>
		<td><span class='textograndenegro'>$montoVentaProductoF</span></td>
		<td><span class='textograndenegro'>$cantidadVentaProductoF</span></td>	
		<td><a href='rptProductoIndCliente.php?rpt_cliente=$codigoCliente&rpt_producto=$rptProducto&fechaInicio=$fechaInicio&fechaFinal=$fechaFinal' 
		target='_blank'>
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
	
	echo "<a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a>
	</center>";
	
?>