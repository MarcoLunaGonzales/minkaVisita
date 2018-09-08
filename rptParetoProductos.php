<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funciones.php");


$rptPromotor=$_GET['rpt_promotor'];
$rptLinea=$_GET['rpt_linea'];
$rptTerritorio=$_GET['rpt_territorio'];
$fechaInicio=$_GET['rpt_fechaini'];
$fechaFinal=$_GET['rpt_fechafin'];
$rptCanal=$_GET['rpt_canal'];
$rptVer=$_GET['rpt_ver'];

$rptVerDesc="";
$campoReporte="";

if($rptVer==1){
	$rptVerDesc="Bolivianos";
	$campoReporte="monto_venta";
}else{
	$rptVerDesc="Unidades";
	$campoReporte="cantidad";
}

$rptLineaX= str_replace("`","'",$rptLinea);
$rptCanalX= str_replace("`","'",$rptCanal);


$datetime1 = date_create($fechaInicio);
$datetime2 = date_create($fechaFinal);
$interval = date_diff($datetime1, $datetime2);
$numeroMeses = ($interval->y * 12) + $interval->m;


/*echo $rptPromotor."<br>";
echo $rptLineaX."<br>";
echo $rptTerritorio."<br>";
echo $fechaInicio." ".$fechaFinal."<br>";*/


echo "<h1>Pareto de Productos</h1>";
echo "<h2>Linea: $rptLineaX<br>Canal: $rptCanalX<br>Rango de Fechas: $fechaInicio   $fechaFinal<br>
Reporte en: $rptVerDesc</h2>";

	echo "<center><table class='texto'>";
	if($rptVer==1){
		echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>&nbsp;</th><th>&nbsp;</th><th>MontoVenta</th><th>Promedio Mes</th>
		<th>Participacion</th><th>% Acumulado</th></tr>";
	}
	else{
		echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Producto</th><th>&nbsp;</th><th>&nbsp;</th><th>CantidadVenta</th><th>Promedio Mes</th>
		<th>Participacion</th><th>% Acumulado</th></tr>";		
	}
	
	$sqlVentaTotal="select sum(v.$campoReporte) from ventas v, productos p
		where v.cod_ciudad in ($rptTerritorio) and v.cod_funcionario in ($rptPromotor) and v.cod_producto in 
		(select pp.cod_producto from productos pp where pp.linea in ($rptLineaX)) and v.canal in ($rptCanalX) and 
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_producto=p.cod_producto";
		//echo $sqlVentaTotal;
	$respVentaTotal=mysql_query($sqlVentaTotal);
	$montoVentaTotal=mysql_result($respVentaTotal,0,0);
	
	
	$sqlClientes="select p.cod_producto, p.nombre_producto, sum(v.$campoReporte) from ventas v, productos p
		where v.cod_ciudad in ($rptTerritorio) and v.cod_funcionario in ($rptPromotor) and v.cod_producto in 
		(select pp.cod_producto from productos pp where pp.linea in ($rptLineaX)) and v.canal in ($rptCanalX) and
		v.fecha_venta BETWEEN '$fechaInicio' and '$fechaFinal' and v.cod_producto=p.cod_producto 
		group by p.cod_producto, p.nombre_producto order by 3 desc";
	$respClientes=mysql_query($sqlClientes);
	
	$indice_tabla=1;
	$porcParetoAcumulado=0;
	$indicePareto=0;
	while($datClientes=mysql_fetch_array($respClientes))
	{
		$codigoProducto=$datClientes[0];
		$nombreProducto=$datClientes[1];
		$montoVentaCliente=$datClientes[2];
		$montoVentaClienteF=formatonumeroDec($montoVentaCliente);
		$porcentajeCliente=($montoVentaCliente/$montoVentaTotal)*100;
		$porcentajeClienteF=formatonumeroDec($porcentajeCliente);
		$porcParetoAcumulado=$porcParetoAcumulado+$porcentajeCliente;
		$porcParetoAcumuladoF=formatonumeroDec($porcParetoAcumulado);
		
		$montoPromedioMeses=formatonumeroDec($montoVentaCliente/$numeroMeses);
		
		if($porcParetoAcumuladoF<=80){
			$color="#8BC34A";
		}else{
			$color="#f44336";
			if($indicePareto==0){
				$indicePareto=$indice_tabla-1;
			}
		}
		echo "<tr><td>$indice_tabla</td><td>$codigoProducto</td><td>$nombreProducto</td>  
			<td><a href='rptProductoIndividual.php?rpt_producto=$codigoProducto&rpt_linea=$rptLinea&rpt_territorio=$rptTerritorio&rpt_fechaini=$fechaInicio&rpt_fechafin=$fechaFinal&rpt_canal=$rptCanal' target='_blank'>
			<img src='imagenes/ruteroaprobado.png' width='30' title='Productos que compra el cliente'></a></td>
			<td><a href='rptNOProductoIndividual.php?rpt_producto=$codigoProducto&rpt_linea=$rptLinea&rpt_territorio=$rptTerritorio&rpt_fechaini=$fechaInicio&rpt_fechafin=$fechaFinal' target='_blank'>
			<img src='imagenes/rutero.png' width='30' title='Productos que no compra el cliente'></a></td>
		<td>$montoVentaClienteF</td>
		<td>$montoPromedioMeses</td><td>$porcentajeClienteF %</td><td bgcolor='$color'>$porcParetoAcumuladoF</td></th>";
		
		$indice_tabla++;
	}
	echo "</table><br>";
	
	$indice_tabla--;
	$porcentajePareto=($indicePareto/$indice_tabla)*100;
	$porcentajePareto=formatonumeroDec($porcentajePareto);
	echo "<div style='border:blue 5px double; border-radius: 20px; position: absolute; top:50; right:100;margin: 0 auto; width: 200; height: 80;' class='textograndenegro'>Total:$indice_tabla <br>Pareto: $indicePareto <br>% Pareto: $porcentajePareto</div>";

	
	echo "<center><a href='javascript:window.print();'><IMG border='no' title='Imprimir' src='imagenes/print.jpg' width='40'></a></center>";
	
?>