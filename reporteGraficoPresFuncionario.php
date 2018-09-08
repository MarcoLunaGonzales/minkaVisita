<html>
<head>
	<link href="stilos.css" rel="stylesheet" type="text/css">
</head>
<?php
	require("conexion.inc");
	require("funcion_nombres.php");
	require("funciones.php");

	$codRegional=$_SESSION['codRegionalTrabajo'];
	$nombreRegional=nombreTerritorio($codRegional);
	$codMes=$_SESSION['mesTrabajo'];
	$codAnio=$_SESSION['anioTrabajo'];
	$nombreMesAvance=$_SESSION['mesTrabajoDesc'];
	$codPromotor=$_SESSION['promotorTrabajo'];
	$codFuncionario=$_COOKIE['global_usuario'];
	$nombrePromotor=nombreVisitador($codFuncionario);
	
?>
<div>	
	<h1>Reporte Avance de Presupuestos x Funcionario y Linea</h1>
	<h2>Regional:  <?php echo $nombreRegional;?></h2>
	<h2>Mes Avance:  <?php echo $nombreMesAvance;?></h2>
	<h2>Promotor:  <?php echo $nombrePromotor;?></h2>
	
</div>

	<!--img src="chartPresRegional.php"-->

<?php
	require("chartPresFuncionario.php");
?>


<body>


<div id="main">
<h1>Detalle Avance de Presupuestos x Funcionario y Linea</h1>
<center>
<table class="texto">
	<thead>
		<tr>
			<th>Linea</th>
			<th>Presupuesto[Bs]</th>
			<th>Venta[Bs]</th>
			<th>% Cumplimiento[Bs]</th>			
			<th>Presupuesto[u]</th>
			<th>Venta[u]</th>
			<th>% Cumplimiento[u]</th>			

		</tr>
	</thead>
	
	<tbody>
	<?php
	$sql="select pp.linea, sum(p.monto_presupuesto), sum(p.cantidad_presupuesto) from presupuestos p, productos pp
		where p.cod_producto=pp.cod_producto and 
		MONTH(p.fecha)='$codMes' and YEAR(p.fecha)='$codAnio' AND
		p.cod_ciudad='$codRegional' and p.cod_funcionario='$codPromotor' group by pp.linea order by 1";
	$resp=mysql_query($sql);
	$totalPresupuesto=0;
	$totalVentas=0;
	$alcanceTotal=0;
	while($dat=mysql_fetch_array($resp)){
	
		$nombreLinea=$dat[0];
		$montoPresupuesto=$dat[1];
		$montoPresupuestoF=formatonumero($montoPresupuesto);
		
		$cantidadPresupuesto=$dat[2];
		$cantidadPresupuestoF=formatonumero($cantidadPresupuesto);
		
		$sqlVenta="select IFNULL(sum(v.monto_venta),0), IFNULL(sum(v.cantidad),0) from ventas v, productos p
			where MONTH(v.fecha_venta)='$codMes' and YEAR(v.fecha_venta)='$codAnio' AND
			v.cod_ciudad='$codRegional' and v.cod_producto=p.cod_producto and p.linea='$nombreLinea' 
			and v.cod_funcionario='$codPromotor' 
			and v.canal not in ('INSTITUCIONAL')";
		$respVenta=mysql_query($sqlVenta);
		$montoVenta=mysql_result($respVenta,0,0);
		$cantidadVenta=mysql_result($respVenta,0,1);

		$montoVentaF=formatonumero($montoVenta);
		$cantidadVentaF=formatonumero($cantidadVenta);
		
		$alcancePres=($montoVenta/$montoPresupuesto)*100;
		$alcancePresF=formatonumero($alcancePres);
		
		$alcancePresCant=($cantidadVenta/$cantidadPresupuesto)*100;
		$alcancePresCantF=formatonumero($alcancePresCant);
		
		$totalPresupuesto=$totalPresupuesto+$montoPresupuesto;
		$totalVentas=$totalVentas+$montoVenta;
				
		echo "<tr class='textograndenegro'><td>$nombreLinea <a href='reportePresPersonalProducto.php?linea=$nombreLinea' target='_blank'><img src='imagenes/detalle3.png' width='40' title='Ver x Producto'></a></td>
		<td align='right'>$montoPresupuestoF</td>
		<td align='right'>$montoVentaF</td>
		<td align='center'>$alcancePresF %</td>
		<td align='right'>$cantidadPresupuestoF</td>
		<td align='right'>$cantidadVentaF</td>
		<td align='center'>$alcancePresCantF %</td>

		</tr>";
		
	}
	$alcanceTotal=($totalVentas/$totalPresupuesto)*100;
	?>
	</tbody>
	<tfoot>
		<tr class="textograndenegro">
			<th>-</th>
			<th><?php echo formatonumero($totalPresupuesto);?></th>
			<th><?php echo formatonumero($totalVentas);?></th>
			<th><?php echo formatonumero($alcanceTotal); ?>%</th>
			<th>-</th>
			<th>-</th>
			<th>-</th>
		</tr>
	</tfoot>
</table>
</center>
</div>

</body>
</html>

