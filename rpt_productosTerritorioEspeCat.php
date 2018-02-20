<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=2; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }
	 
	 //aqui sacamos los porcentajes 
	 for(var i=8; i<=numCols+1; i=i+7){
	 		main.rows[numFilas-1].cells[i].innerHTML=Math.round((main.rows[numFilas-1].cells[i-2].innerHTML/main.rows[numFilas-1].cells[i-1].innerHTML)*100);
	 }	 
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_producto=$_GET['rpt_producto'];
$rpt_espe1=str_replace("`","'",$rpt_espe);
$rpt_cat=$_GET['rpt_cat'];
$rpt_nombreCat1=$_GET['rpt_cat1'];
$rpt_cat1=str_replace("`","'",$rpt_cat);


echo "<html><body onload='totales();'>";

echo "<table border='0' class='textotit' align='center'><tr><th>Resumen por Producto, Territorio, Especialidad y Categoria de Medico<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe Categoria: $rpt_nombreCat1
</th></tr></table></center><br>";

	$sqlTerritorio="select c.`cod_ciudad`, c.`descripcion` from ciudades c where c.cod_ciudad in ($rpt_territorio) order by c.`descripcion`";
	$respTerritorio=mysql_query($sqlTerritorio);
	$filasTerritorio=mysql_num_rows($respTerritorio);
	$filasSpan=$filasTerritorio+3;
	echo "<table border=1 class='texto' cellspacing='0' id='main' align='center' width='55%'>";
	echo "<tr><th>Especialidad</th><th>Cat.</th>";

	while($datTerritorio=mysql_fetch_array($respTerritorio)){
		$codCiudad=$datTerritorio[0];
		$nombreCiudad=$datTerritorio[1];
		echo "<th colspan=7>$nombreCiudad</th>";
	}
	echo "<th colspan=7>Total</th></tr><tr><th>&nbsp;</th><th>&nbsp;</th>";
	$respTerritorio=mysql_query($sqlTerritorio);
	while($datTerritorio=mysql_fetch_array($respTerritorio)){
		echo "<th>Alta</th>";
		echo "<th>Media</th>";
		echo "<th>Baja</th>";
		echo "<th>No Utiliza</th>";
		echo "<th>Subtotal</th>";
		echo "<th>Subtotal Univ. Enc.</th>";
		echo "<th>%</th>";
	}
		echo "<th>Alta</th>";
		echo "<th>Media</th>";
		echo "<th>Baja</th>";
		echo "<th>No Utiliza</th>";
		echo "<th>Subtotal</th>";
		echo "<th>Subtotal Univ. Enc.</th>";
		echo "<th>%</th></tr>";
	
	$sql="select e.`cod_especialidad`
		from `especialidades` e where e.`cod_especialidad` in ($rpt_espe1)";
	$resp=mysql_query($sql);
	$sumaFrecuencia=0;

	while($dat=mysql_fetch_array($resp)){
		$codEspe=$dat[0];
	
		$sqlCat="select c.`categoria_med` from `categorias_medicos` c where c.`categoria_med` in ($rpt_cat1) order by 1";
		$respCat=mysql_query($sqlCat);
		while($datCat=mysql_fetch_array($respCat)){
			$codCat=$datCat[0];
			
			echo "<tr><td>$codEspe</td><td>$codCat</td>";
		
			$cant1=0;
			$cant2=0;
			$cant3=0;
			$cant4=0;
		
			$sqlTerritorio="select c.`cod_ciudad`, c.`descripcion` from ciudades c where c.cod_ciudad in ($rpt_territorio) order by c.`descripcion`";
			$respTerritorio=mysql_query($sqlTerritorio);
			$indice=1;
			$totalMedEncuestados=0;
			$totalCatEspeTotal=0;
			while($datTerritorio=mysql_fetch_array($respTerritorio)){
				$codCiudad=$datTerritorio[0];
				$totalCatEspe=0;
				for($ii=1;$ii<=4;$ii++){
					$sqlProdTerritorio="select count(e.`frecuencia`) from `encuestamedicos` e, `medicos_a_encuestar` me, medicos m 
				 		where e.`cod_med` = me.`cod_med` and me.`cod_espe` in ('$codEspe') and e.cod_med = m.cod_med and 
				 		me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($codCiudad) and e.`frecuencia` = $ii  and e.`cod_prod`='$rpt_producto' 
				 		and me.`cod_cat` in ('$codCat') group by e.`cod_prod`"; 
					$respProdTerritorio=mysql_query($sqlProdTerritorio);
					$filasProdTerritorio=mysql_num_rows($respProdTerritorio);
					if($filasProdTerritorio>0){
						$frecuenciaProdTerritorio=mysql_result($respProdTerritorio,0,0);
					}else{
						$frecuenciaProdTerritorio=0;
					}
					
					if($ii==1){
						$cant1=$cant1+$frecuenciaProdTerritorio;
					}
					if($ii==2){
						$cant2=$cant2+$frecuenciaProdTerritorio;
					}
					if($ii==3){
						$cant3=$cant3+$frecuenciaProdTerritorio;
					}
					if($ii==4){
						$cant4=$cant4+$frecuenciaProdTerritorio;
					}
					echo "<td>$frecuenciaProdTerritorio</td>";
					$indice++;
					$totalCatEspe=$totalCatEspe+$frecuenciaProdTerritorio;
				}
				
				$sqlUniv="select count(*) from `medicos_a_encuestar` me, medicos m 
					where me.cod_med=m.`cod_med` and m.`cod_ciudad` in ($codCiudad) and me.`cod_espe` in ('$codEspe') and me.`cod_cat` in ('$codCat')";
				$respUniv=mysql_query($sqlUniv);
				$filasUniv=mysql_num_rows($respUniv);
				if($filasUniv>0){
					$totalUnivCat=mysql_result($respUniv,0,0);
				}else{
					$totalUnivCat=0;
				}
				$totalMedEncuestados=$totalMedEncuestados+$totalUnivCat;
				$porcentajeUniv=round(($totalCatEspe/$totalUnivCat)*100);	
				$totalCatEspeTotal=$totalCatEspeTotal+$totalCatEspe;
				echo "<th>$totalCatEspe</th>";
				echo "<th>$totalUnivCat</th>";
				echo "<th>$porcentajeUniv</th>";
			}


			$porcentajeUniv=round(($totalCatEspeTotal/$totalMedEncuestados)*100);	
			echo "<th>$cant1</th>";
			echo "<th>$cant2</th>";
			echo "<th>$cant3</th>";
			echo "<th>$cant4</th>";
			echo "<th>$totalCatEspeTotal</th>";
			echo "<th>$totalMedEncuestados</th>";
			echo "<th>$porcentajeUniv</th></tr>";
		}
	}	
	echo "<tr><th></th><TH>TOTALES</TH></tr>";
	echo "</table><br>";

?>