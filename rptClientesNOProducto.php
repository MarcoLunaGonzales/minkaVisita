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


$datetime1 = date_create($fechaInicio);
$datetime2 = date_create($fechaFinal);
$interval = date_diff($datetime1, $datetime2);

echo "<h1>Productos que NO compra un Cliente Individual</h1>";
echo "<h2>Cliente: $nombreRptCliente <br>Linea: $rptLineaX<br>Rango de Fechas: $fechaInicio   $fechaFinal</h2>";

	echo "<center><table class='texto'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>Ultima Venta</th><th>Cantidad Ultima Compra</th></tr>";
			
	$sqlProductos="select p.cod_producto, p.nombre_producto from productos p
		where p.cod_producto not in (select v.cod_producto from ventas v where v.fecha_venta 
		BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_cliente in ($rptCliente)) order by 2";


	$respProductos=mysql_query($sqlProductos);
	
	$indice_tabla=1;
	while($datProductos=mysql_fetch_array($respProductos))
	{
		$codigoProducto=$datProductos[0];
		$nombreProducto=$datProductos[1];
		
		$sqlUltimaVenta="select DATE_FORMAT(max(v.fecha_venta), '%d/%m/%Y'), v.cantidad from ventas v where v.cod_cliente='$rptCliente' and 
			v.cod_producto='$codigoProducto'";
		$respUltimaVenta=mysql_query($sqlUltimaVenta);
		$fechaUltimaVenta="-";
		$cantidadUltimaVenta=0;
		$numRespuesta=mysql_num_rows($respUltimaVenta);
		if($numRespuesta>0){
			$fechaUltimaVenta=mysql_result($respUltimaVenta,0,0);
			$cantidadUltimaVenta=mysql_result($respUltimaVenta,0,1);
		}
		
		echo "<tr><td>$indice_tabla</td><td>$codigoProducto</td><td>$nombreProducto</td>
		<td>$fechaUltimaVenta</td>
		<td align='center'>$cantidadUltimaVenta</td></th>";
		
		$indice_tabla++;
	}
	$montoTotalF=formatonumeroDec($montoTotal);
	echo "</table><br>";
	
	echo "<table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.jpg' width='40'></a></td></tr></table></center>";
	
?>