<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main1');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=2; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }
	 
	 main=document.getElementById('main2');   
   numFilas=main.rows.length;
   numCols=main.rows[2].cells.length;
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
	 
	 main=document.getElementById('main3');   
   numFilas=main.rows.length;
   numCols=main.rows[2].cells.length;
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
	 
	 main=document.getElementById('main4');   
   numFilas=main.rows.length;
   numCols=main.rows[2].cells.length;
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
}
</script>


<?php
require("conexion.inc");
require("estilos_reportes.inc");
$rpt_territorio=$_GET["rpt_territorio"];
$rpt_nombreTerritorio=$_GET['rpt_nombreTerritorio'];
$rpt_espe=$_GET['rpt_espe'];
$rpt_nombreEspe=$_GET['rpt_espe1'];
$rpt_espe1=str_replace("`","'",$rpt_espe);
$rpt_cat=$_GET['rpt_cat'];
$rpt_nombreCat1=$_GET['rpt_cat1'];
$rpt_cat1=str_replace("`","'",$rpt_cat);


echo "<html><body onload='totales();'>";

echo "<table border='0' class='textotit' align='center'><tr><th>Productos Mas Utilizados segun Encuesta Detallado por Territorio<br>
Territorio: $rpt_nombreTerritorio  Especialidad: $rpt_nombreEspe Categoria: $rpt_nombreCat1
</th></tr></table></center><br>";

echo "<table border='0' class='textomini' align='center'>";
echo "<tr><td>Leyenda:</td><th bgcolor='#ff0000'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th><th align='Left'>Productos no incluidos en encuesta</th></td></tr>";
echo "</table>";



for($i=1;$i<=4;$i++){
	if($i==1){
		$nombreFrecuencia="Alta";
	}
	if($i==2){
		$nombreFrecuencia="Media";
	}
	if($i==3){
		$nombreFrecuencia="Baja";
	}
	if($i==4){
		$nombreFrecuencia="No Utiliza";
	}
	$sqlTerritorio="select c.`cod_ciudad`, c.`descripcion` from ciudades c where c.cod_ciudad in ($rpt_territorio) order by c.`descripcion`";
	$respTerritorio=mysql_query($sqlTerritorio);
	$filasTerritorio=mysql_num_rows($respTerritorio);
	$filasSpan=($filasTerritorio)+3;
	echo "<table border=1 class='texto' cellspacing='0' id='main$i' align='center' width='55%'>";
	echo "<tr><th colspan='$filasSpan'>$nombreFrecuencia</th></tr>";
	echo "<tr><th>&nbsp;</th><th>Producto</th>";

	while($datTerritorio=mysql_fetch_array($respTerritorio)){
		$codCiudad=$datTerritorio[0];
		$nombreCiudad=$datTerritorio[1];
		echo "<th>$nombreCiudad</th>";
	}
	echo "<th>Total Pais</th></tr>";
	
	$sql="select (select concat(mm.descripcion,' ',mm.presentacion) from muestras_medicas mm where mm.codigo =  e.cod_prod), 
				e.`cod_prod`, count(e.`frecuencia`) from `encuestamedicos` e, `medicos_a_encuestar` me, 
				medicos m where e.`cod_med` = me.`cod_med` and me.`cod_espe` in ($rpt_espe1) 
				and e.cod_med = m.cod_med and me.`cod_cat` in ($rpt_cat1) and
				me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($rpt_territorio) and e.`frecuencia` = $i group by e.`cod_prod` order by 3 desc;";
	$resp=mysql_query($sql);
	$indice=1;
	$sumaFrecuencia=0;
	while($dat=mysql_fetch_array($resp)){
		$nombreProducto=$dat[0];
		$codProducto=$dat[1];

		$sqlVeriProducto="select * from `producto_especialidad` p where p.`codigo_linea`=1021 and p.`cod_especialidad` in ($rpt_espe1)
			and p.`codigo_mm`='$codProducto'";
		$respVeriProducto=mysql_query($sqlVeriProducto);
		$numFilasVeri=mysql_num_rows($respVeriProducto);
		if($numFilasVeri>0){
			$colorFila="";
		}else{
			$colorFila="#ff0000";
		}
		$frecuencia=$dat[2];
		$sumaFrecuencia=$sumaFrecuencia+$frecuencia;
		echo "<tr bgcolor='$colorFila'><td>$indice</td><td>$nombreProducto</td>";
		
		$sqlTerritorio="select c.`cod_ciudad`, c.`descripcion` from ciudades c where c.cod_ciudad in ($rpt_territorio) order by c.`descripcion`";
		$respTerritorio=mysql_query($sqlTerritorio);
		while($datTerritorio=mysql_fetch_array($respTerritorio)){
			$codCiudad=$datTerritorio[0];
			$nombreCiudad=$datTerritorio[1];
			
			$sqlProdTerritorio="select count(e.`frecuencia`) from `encuestamedicos` e, `medicos_a_encuestar` me, medicos m 
			 	where e.`cod_med` = me.`cod_med` and me.`cod_espe` in ($rpt_espe1) 
			 	and e.cod_med = m.cod_med and me.`cod_cat` in ($rpt_cat1) and
			 	me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($codCiudad) and e.`frecuencia` = $i  and e.`cod_prod`='$codProducto' 
			 	group by e.`cod_prod`"; 
			$respProdTerritorio=mysql_query($sqlProdTerritorio);
			$filasProdTerritorio=mysql_num_rows($respProdTerritorio);
			if($filasProdTerritorio>0){
				$frecuenciaProdTerritorio=mysql_result($respProdTerritorio,0,0);
			}else{
				$frecuenciaProdTerritorio=0;
			}

			/*$sqlProdTerritorio="select count(e.`frecuencia`) from `encuestamedicos` e, `medicos_a_encuestar` me, medicos m 
			 	where e.`cod_med` = me.`cod_med` and me.`cod_espe` in ($rpt_espe1) and e.cod_med = m.cod_med and 
			 	me.`cod_med` = m.cod_med and m.`cod_ciudad` in ($codCiudad) and e.`cod_prod`='$codProducto' 
			 	group by e.`cod_prod`"; 
			$respProdTerritorio=mysql_query($sqlProdTerritorio);
			$filasProdTerritorio=mysql_num_rows($respProdTerritorio);
			if($filasProdTerritorio>0){
				$frecuenciaMedResp=mysql_result($respProdTerritorio,0,0);
			}else{
				$frecuenciaMedResp=0;
			}

			$sqlProdTerritorio="select count(*) from `medicos_a_encuestar` me, medicos m 
				where me.cod_med = m.`cod_med` and m.`cod_ciudad` in ($codCiudad) and me.`cod_espe` in ($rpt_espe1)"; 
			$respProdTerritorio=mysql_query($sqlProdTerritorio);
			$filasProdTerritorio=mysql_num_rows($respProdTerritorio);
			if($filasProdTerritorio>0){
				$totalMed=mysql_result($respProdTerritorio,0,0);
			}else{
				$totalMed=0;
			}*/

			
			echo "<th>$frecuenciaProdTerritorio</th>";
		}
		echo "<th>$frecuencia</th></tr>";
		$indice++;
	}	
	echo "<tr><th></th><TH>TOTALES</TH></tr>";
	echo "</table><br>";
}



?>