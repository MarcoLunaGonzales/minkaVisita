<?php
	require("conexion.inc");
	require("funcion_nombres.php");
	require("funciones.php");

	$codMed=$_GET['codMed'];
	$codMedCUP=$_GET['codCUP'];

	$nombreMedico=nombreMedico($codMed);
	$nombreMedicoCUP=nombreMedicoCUP($codMedCUP);
?>
<div>	
	<h1>Analisis de Informacion CUP por Mercado</h1>
	<h2>Medico Gales:<?php echo $nombreMedico;?></h2>
	<h2>Medico CUP:<?php echo $nombreMedicoCUP;?></h2>
</div>
<?php
	require("chartCupMercado.php");
	
	/*IMPORTANTE ACA ESTA EL PREFIJO PARA LOS MERCADOS
	*/
	$prefijoMercado="P-";
?>

<html>
<head>
	<title>Datos CUP x Mercado</title>
	<link rel="stylesheet" href="tablesorterLibs/docs/css/jq.css" type="text/css" media="print, projection, screen" />
	<link rel="stylesheet" href="tablesorterLibs/themes/blue/style.css" type="text/css" media="print, projection, screen" />
	<script type="text/javascript" src="tablesorterLibs/jquery-latest.js"></script>
	<script type="text/javascript" src="tablesorterLibs/jquery.tablesorter.js"></script>
	<script type="text/javascript" src="tablesorterLibs/addons/pager/jquery.tablesorter.pager.js"></script>
	<script type="text/javascript" src="tablesorterLibs/docs/js/chili/chili-1.8b.js"></script>
	<script type="text/javascript" src="tablesorterLibs/docs/js/docs.js"></script>
	<script type="text/javascript">
	$(function() {
		$("table")
			.tablesorter({widthFixed: true, widgets: ['zebra']})
			.tablesorterPager({container: $("#pager")});
	});
	</script>
</head>
<body>


<div id="main">
<h1>Detalle por Mercado</h1>
<?php
	$vectorFechas=fechasNombresCUP();
	$vectorCUPAnioMovil=fechasCUPAnioMovil();
	$vectorTrimIni=fechasCUPTrimestreIni();
	$vectorTrimFin=fechasCUPTrimestreFin();
?>
<table cellspacing="1" class="tablesorter" width="80%">
	<thead>
		<tr>
			<th>Mercado</th>
			<th><?php echo $vectorFechas[0]; ?></th>
			<th><?php echo $vectorFechas[1]; ?></th>
			<th><?php echo $vectorFechas[2]; ?></th>
			<th><?php echo $vectorFechas[3]; ?></th>
			<th>Total</th>
		</tr>
	</thead>
	
	<tbody>
	<?php
		$sqlMercado="select m.id_mercado, m.mercado, IFNULL(sum(d.cant_pxs1),0) from cup_datos d, cup_mercado m, cup_mercados_productos mp
		where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
		d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and d.id_medico='$codMedCUP' 
		and d.fecha BETWEEN 
		'$vectorCUPAnioMovil[0]' and '$vectorCUPAnioMovil[1]' and m.mercado like '$prefijoMercado%'
		group by m.id_mercado, m.mercado order by 3 desc;";
		$respMercado=mysql_query($sqlMercado);
		$sumTotal=0;
		while($datMercado=mysql_fetch_array($respMercado)){
			$idMercado=$datMercado[0];
			$nombreMercado=$datMercado[1];
			$cantPxAnio=$datMercado[2];
			$sumTotal=$sumTotal+$cantPxAnio;
			
			echo "<tr><td>$nombreMercado &nbsp; <a href='verificaCUP.php?codCUP=$codMedCUP&codMed=$codMed&idMercado=$idMercado&parametro=3' target='_blank'>
			<img src='imagenes/reportcup2.png' width='12' title='Ver por Producto'></a></td>";
			
			for($ii=0;$ii<=3;$ii++){
				$fechaIni=$vectorTrimIni[$ii];
				$fechaFin=$vectorTrimFin[$ii];
				
				$sqlTrimMercado="select IFNULL(sum(d.cant_pxs1),0) from cup_datos d, cup_mercado m, cup_mercados_productos mp
				where d.id_raiz=mp.id_raiz and d.id_presentacion=mp.id_presentacion and d.id_concentracion=mp.id_concentracion and 
				d.id_formaf=mp.id_formaf and m.id_mercado=mp.id_mercado and d.id_medico='$codMedCUP' and m.id_mercado='$idMercado'
				and d.fecha BETWEEN 
				'$fechaIni' and '$fechaFin'";

				$respTrimMercado=mysql_query($sqlTrimMercado);
				$numFilas=mysql_num_rows($respTrimMercado);
				$pxTrim=mysql_result($respTrimMercado,0,0);
				echo "<td>$pxTrim</td>";
			}
			echo "<td>$cantPxAnio</td></tr>";
		}
	?>
	</tbody>
	<tfoot>
		<tr>
			<th>Mercado</th>
			<th><?php echo $vectorFechas[0]; ?></th>
			<th><?php echo $vectorFechas[1]; ?></th>
			<th><?php echo $vectorFechas[2]; ?></th>
			<th><?php echo $vectorFechas[3]; ?></th>
			<th><?php echo $sumTotal; ?></th>
		</tr>
	</tfoot>
</table>
<div id="pager" class="pager">
	<form>
		<img src="tablesorterLibs/addons/pager/icons/first.png" class="first"/>
		<img src="tablesorterLibs/addons/pager/icons/prev.png" class="prev"/>
		<input type="text" class="pagedisplay"/>
		<img src="tablesorterLibs/addons/pager/icons/next.png" class="next"/>
		<img src="tablesorterLibs/addons/pager/icons/last.png" class="last"/>
		<select class="pagesize">
			<option selected="selected"  value="10">10</option>
			<option value="20">20</option>
			<option value="30">30</option>
			<option  value="40">40</option>
		</select>
	</form>
</div>

</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript"></script>
<script type="text/javascript">
_uacct = "UA-2189649-2";
urchinTracker();
</script>
</body>
</html>

