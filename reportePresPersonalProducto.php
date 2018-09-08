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
	$codPromotorTrabajo=$_SESSION['promotorTrabajo'];
	$nombreLinea=$_GET['linea'];
	
?>
<div>	
	<h1>Reporte Avance de Presupuestos x Funcionario y Producto</h1>
	<h2>Regional:  <?php echo $nombreRegional;?></h2>
	<h2>Mes Avance:  <?php echo $nombreMesAvance;?></h2>
	<h2>Linea:  <?php echo $nombreLinea;?></h2>
</div>

<body>

<div id="main">
<center>
<table class="texto">
	<thead>
		<tr>
			<th>Producto</th>
			<th>Presupuesto[Bs]</th>
			<th>Venta[Bs]</th>
			<th>% Cumplimiento[Bs]</th>
			<th>-</th>
			<th>Presupuesto[u]</th>
			<th>Venta[u]</th>
			<th>% Cumplimiento[u]</th>
			<th>-</th>

		</tr>
	</thead>
	
	<tbody>
	<?php
	$sql="select pp.cod_producto, pp.nombre_producto, sum(p.monto_presupuesto), sum(p.cantidad_presupuesto) from presupuestos p, productos pp
		where p.cod_producto=pp.cod_producto and 
		MONTH(p.fecha)='$codMes' and YEAR(p.fecha)='$codAnio' AND
		p.cod_ciudad='$codRegional' and pp.linea='$nombreLinea' 
		and p.cod_funcionario='$codPromotorTrabajo'
		group by pp.cod_producto, pp.nombre_producto order by 1";
	$resp=mysql_query($sql);
	$totalPresupuesto=0;
	$totalVentas=0;
	$alcanceTotal=0;
	while($dat=mysql_fetch_array($resp)){
	
		$codProducto=$dat[0];
		$nombreProducto=$dat[1];
		$montoPresupuesto=$dat[2];
		$cantidadPresupuesto=$dat[3];
		
		$montoPresupuestoF=formatonumero($montoPresupuesto);
		$cantidadPresupuestoF=formatonumero($cantidadPresupuesto);
		
		$sqlVenta="select IFNULL(sum(v.monto_venta),0), IFNULL(sum(v.cantidad),0)  from ventas v, productos p
			where MONTH(v.fecha_venta)='$codMes' and YEAR(v.fecha_venta)='$codAnio' AND
			v.cod_ciudad='$codRegional' and v.cod_producto=p.cod_producto and p.cod_producto='$codProducto' 
			and v.cod_funcionario='$codPromotorTrabajo'";
			
		$respVenta=mysql_query($sqlVenta);
		$montoVenta=mysql_result($respVenta,0,0);
		$cantidadVenta=mysql_result($respVenta,0,1);
		
		$montoVentaF=formatonumero($montoVenta);
		$cantidadVentaF=formatonumero($cantidadVenta);
		
		$alcancePres=0;
		$alcancePresCant=0;
		
		if($montoPresupuesto>0){
			$alcancePres=($montoVenta/$montoPresupuesto)*100;	
			$alcancePresCant=($cantidadVenta/$cantidadPresupuesto)*100;			
		}
		$alcancePresF=formatonumero($alcancePres);
		$alcancePresCantF=formatonumero($alcancePresCant);
		
		$totalPresupuesto=$totalPresupuesto+$montoPresupuesto;
		$totalVentas=$totalVentas+$montoVenta;
		
		if($alcancePres>=100){
			$imgAlcance="<img src='imagenes/good.png' width='40'>";
		}
		if($alcancePres>=50 && $alcancePres<100){
			$imgAlcance="<img src='imagenes/neutral.png' width='40'>";
		}
		if($alcancePres<50){
			$imgAlcance="<img src='imagenes/not.png' width='40'>";
		}
		
		if($alcancePresCant>=100){
			$imgAlcance2="<img src='imagenes/good.png' width='40'>";
		}
		if($alcancePresCant>=50 && $alcancePresCant<100){
			$imgAlcance2="<img src='imagenes/neutral.png' width='40'>";
		}
		if($alcancePresCant<50){
			$imgAlcance2="<img src='imagenes/not.png' width='40'>";
		}
		
		echo "<tr class='textograndenegro'><td>$nombreProducto</td>
		<td align='right'>$montoPresupuestoF</td>
		<td align='right'>$montoVentaF</td>
		<td align='center'>$alcancePresF %</td>
		<td align='center'>$imgAlcance</td>
		<td align='right'>$cantidadPresupuestoF</td>
		<td align='right'>$cantidadVentaF</td>
		<td align='center'>$alcancePresCantF %</td>
		<td align='center'>$imgAlcance2</td>

		</tr>";
		
	}
	$alcanceTotal=0;
	if($totalPresupuesto>0){
		$alcanceTotal=($totalVentas/$totalPresupuesto)*100;	
	}
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
			<th>-</th>
			<th>-</th>
		</tr>
	</tfoot>
</table>
</center>
</div>

</body>
</html>

