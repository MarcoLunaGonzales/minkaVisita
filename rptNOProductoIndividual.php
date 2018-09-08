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
$rptLineaX= str_replace("`","'",$rptLinea);


$datetime1 = date_create($fechaInicio);
$datetime2 = date_create($fechaFinal);
$interval = date_diff($datetime1, $datetime2);

echo "<h1>Clientes que NO compran un Producto Individual</h1>";
echo "<h2>Territorio: $nombreTerritorio <br> Producto: $nombreRptProducto <br>
Rango de Fechas: $fechaInicio   $fechaFinal</h2>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Cliente</th><th>Ultima Venta</th><th>Cantidad Ultima Compra</th></tr>";
		
	$sqlProductos="select c.cod_cliente, c.nombre_cliente from clientes c where 
		c.cod_cliente not in (select v.cod_cliente from ventas v
		where v.cod_ciudad in ($rptTerritorio) and 
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_producto in ($rptProducto)) 
		order by 2";
	
	//echo $sqlProductos;
	$respProductos=mysql_query($sqlProductos);
	
	$indice_tabla=1;
	$porcParetoAcumulado=0;
	$montoTotal=0;
	while($datProductos=mysql_fetch_array($respProductos))
	{
		$codigoCliente=$datProductos[0];
		$nombreCliente=$datProductos[1];
		$montoVentaProducto=$datProductos[2];
		$montoVentaProductoF=formatonumeroDec($montoVentaProducto);

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
		<td>$fechaUltimaVenta</td>
		<td align='center'>$cantidadUltimaVenta</td></th>";
		
		$indice_tabla++;
	}
	$montoTotalF=formatonumeroDec($montoTotal);
	echo "<tr><td>-</td><td>-</td><td>TOTALES</td>
	<td><span class='textograndenegro'>$montoTotalF</span></td>
	<td>-</td>
	<td>-</td>
	<td align='center'>-</td></th>";
	echo "</table><br>";
	
	echo "<table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a></td></tr></table></center>";
	
?>