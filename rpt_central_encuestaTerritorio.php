<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[2].cells.length;
	 
	 for(var j=1; j<=numCols-1; j++){
	 		var subtotal=0;
	 		for(var i=2; i<=numFilas-2; i++){
	 				var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	 }	 
	 
	 //aqui sacamos los porcentajes 
	 for(var i=10; i<=numCols-1; i=i+10){
	 		main.rows[numFilas-1].cells[i-3].innerHTML=Math.round((main.rows[numFilas-1].cells[i-6].innerHTML/main.rows[numFilas-1].cells[i-9].innerHTML)*100);
	 		main.rows[numFilas-1].cells[i-2].innerHTML=Math.round((main.rows[numFilas-1].cells[i-5].innerHTML/main.rows[numFilas-1].cells[i-8].innerHTML)*100);
	 		main.rows[numFilas-1].cells[i-1].innerHTML=Math.round((main.rows[numFilas-1].cells[i-4].innerHTML/main.rows[numFilas-1].cells[i-7].innerHTML)*100);
	 		var sumaCP=(main.rows[numFilas-1].cells[i-9].innerHTML)*1+(main.rows[numFilas-1].cells[i-8].innerHTML)*1+(main.rows[numFilas-1].cells[i-7].innerHTML)*1;
	 		var sumaCR=(main.rows[numFilas-1].cells[i-6].innerHTML)*1+(main.rows[numFilas-1].cells[i-5].innerHTML)*1+(main.rows[numFilas-1].cells[i-4].innerHTML)*1;
	 		main.rows[numFilas-1].cells[i].innerHTML=Math.round((sumaCR/sumaCP)*100);
	 }	 
	 
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");

$rpt_territorio=$_GET["rpt_territorio"];

$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad in ($rpt_territorio)";
$resp1=mysql_query($sql_cab);
$dato=mysql_fetch_array($resp1);
$nombre_territorio=$dato[1];	
$cad_territorio="<br>Territorio: $nombre_territorio";

echo "<html><body onload='totales();'>";
echo "<table border='0' class='textotit' align='center'><tr><th>Cobertura de Encuestas x Territorio<br>
</th></tr></table></center><br>";

	echo "<table border=1 class='texto' align='center' cellspacing=0 id='main'>";
	
	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
	$resp=mysql_query($sql);
	echo "<tr><th>&nbsp;Especialidad</th>";
	while($dat=mysql_fetch_array($resp))
	{	$codCiudad=$dat[0];
		$nombreCiudad=$dat[1];
		echo "<th colspan=10>$nombreCiudad</th>";
	}
	echo "<th colspan=10>TOTALES</th>";
	
	$resp=mysql_query($sql);
	
	echo "<tr><th>&nbsp;</th>";
	while($dat=mysql_fetch_array($resp))
	{	echo "<th>CP A</th><th>CP B</th><th>CP C</th><th>CR A</th><th>CR B</th><th>CR C</th><th>% A</th><th>% B</th><th>% C</th><th>% Total</th>";
	}
	echo "<th>CP A</th><th>CP B</th><th>CP C</th><th>CR A</th><th>CR B</th><th>CR C</th><th>% A</th><th>% B</th><th>% C</th><th>% Total</th></tr>";

	
	$sql_espe="select distinct (m.`cod_espe`) from `medicos_a_encuestar` m where m.`cod_visitador` in (select codigo_funcionario from funcionarios where
			cod_ciudad = $codCiudad) order by m.`cod_espe`";	
	$resp_espe=mysql_query($sql_espe);
	while($dat_espe=mysql_fetch_array($resp_espe))
	{	$codEspe=$dat_espe[0];

		$cad_mostrar="<tr><td>$codEspe</td>";
		
		$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad in ($rpt_territorio) order by descripcion";
		$resp=mysql_query($sql);

		$totalVisPlanA=0;
		$totalVisPlanB=0;
		$totalVisPlanC=0;
		$totalVisRegA=0;
		$totalVisRegB=0;
		$totalVisRegC=0;
		
		while($dat=mysql_fetch_array($resp))
		{	
			$codCiudad=$dat[0];
			
			//PLANIFICADO
			$contPlanA=0;
			$contPlanB=0;
			$contPlanC=0;
			$totalContPlan=0;
			$sqlPlani="select count(m.`cod_med`) from `medicos_a_encuestar` m  
			where m.`cod_visitador` in (select codigo_funcionario from funcionarios where
			cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe' and m.`cod_cat`='A'";
			$respPlani=mysql_query($sqlPlani);
			$contPlanA=mysql_result($respPlani,0,0);
			
			$sqlPlani="select count(m.`cod_med`) from `medicos_a_encuestar` m  
			where m.`cod_visitador` in (select codigo_funcionario from funcionarios where
			cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe' and m.`cod_cat`='B'";
			$respPlani=mysql_query($sqlPlani);
			$contPlanB=mysql_result($respPlani,0,0);
			
			$sqlPlani="select count(m.`cod_med`) from `medicos_a_encuestar` m  
			where m.`cod_visitador` in (select codigo_funcionario from funcionarios where
			cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe' and m.`cod_cat`='C'";
			$respPlani=mysql_query($sqlPlani);
			$contPlanC=mysql_result($respPlani,0,0);
			
			$totalContPlan=$contPlanA+$contPlanB+$contPlanC;
			//ENCUESTAS REGISTRADAS
			$contRegA=0;
			$contRegB=0;
			$contRegC=0;
			$totalContReg=0;
			$sqlReg="select * from `medicos_a_encuestar` m, `encuestamedicos` e 
				where m.`cod_med`=e.`cod_med` and m.`cod_visitador` in (select codigo_funcionario from funcionarios where
				cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe'
				and m.`cod_cat`='A' group by m.cod_visitador, m.cod_med";
			$respReg=mysql_query($sqlReg);
			$contRegA=mysql_num_rows($respReg);
			
			$sqlReg="select count(distinct(m.cod_med)) from `medicos_a_encuestar` m, `encuestamedicos` e 
				where m.`cod_med`=e.`cod_med` and m.`cod_visitador` in (select codigo_funcionario from funcionarios where
				cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe'
				and m.`cod_cat`='B' group by m.cod_visitador, m.cod_med";
			$respReg=mysql_query($sqlReg);
			$contRegB=mysql_num_rows($respReg);
			
			$sqlReg="select count(distinct(m.cod_med)) from `medicos_a_encuestar` m, `encuestamedicos` e 
				where m.`cod_med`=e.`cod_med` and m.`cod_visitador` in (select codigo_funcionario from funcionarios where
				cod_ciudad = $codCiudad) and m.`cod_espe`='$codEspe'
				and m.`cod_cat`='C' group by m.cod_visitador, m.cod_med";
			$respReg=mysql_query($sqlReg);
			$contRegC=mysql_num_rows($respReg);
			
			$totalContReg=$contRegA+$contRegB+$contRegC;
					
			if($contPlanA!=0){
				$porcA=round(($contRegA/$contPlanA)*100);
			}else{
				$porcA=0;
			}			
			if($contPlanB!=0){
				$porcB=round(($contRegB/$contPlanB)*100);
			}else{
				$porcB=0;
			}
			if($contPlanC!=0){
				$porcC=round(($contRegC/$contPlanC)*100);
			}else{
				$porcC=0;
			}

			if($totalContPlan!=0){
				$porcTotal=round(($totalContReg/$totalContPlan)*100);
			}else{
				$porcTotal=0;
			}
			$cad_mostrar.="<td>$contPlanA</td><td>$contPlanB</td><td>$contPlanC</td><td>$contRegA</td><td>$contRegB</td><td>$contRegC</td>
			<td>$porcA</td><td>$porcB</td><td>$porcC</td><th>$porcTotal</th>";
			
			
			$totalVisPlanA=$totalVisPlanA+$contPlanA;
			$totalVisPlanB=$totalVisPlanB+$contPlanB;
			$totalVisPlanC=$totalVisPlanC+$contPlanC;
			$totalVisRegA=$totalVisRegA+$contRegA;
			$totalVisRegB=$totalVisRegB+$contRegB;
			$totalVisRegC=$totalVisRegC+$contRegC;
			
		}
		$totalVisPlan=$totalVisPlanA+$totalVisPlanB+$totalVisPlanC;
		$totalVisReg=$totalVisRegA+$totalVisRegB+$totalVisRegC;
		if($totalVisPlanA!=0){
				$porcTotalVisA=round(($totalVisRegA/$totalVisPlanA)*100);
		}else{
				$porcTotalVisA=0;
		}	
		if($totalVisPlanB!=0){
				$porcTotalVisB=round(($totalVisRegB/$totalVisPlanB)*100);
		}else{
				$porcTotalVisB=0;
		}
		if($totalVisPlanC!=0){
				$porcTotalVisC=round(($totalVisRegC/$totalVisPlanC)*100);
		}else{
				$porcTotalVisC=0;
		}
		if($totalVisPlan!=0){
				$porcTotalVisitadores=round(($totalVisReg/$totalVisPlan)*100);
		}else{
				$porcTotalVisitadores=0;
		}
		$cad_mostrar.="<td>$totalVisPlanA</td><td>$totalVisPlanB</td><td>$totalVisPlanC</td><td>$totalVisRegA</td><td>$totalVisRegB</td>
		<td>$totalVisRegC</td><td>$porcTotalVisA</td><td>$porcTotalVisB</td><td>$porcTotalVisC</td><th>$porcTotalVisitadores</th></tr>";
		echo $cad_mostrar;			
}			
echo "<tr><TH>TOTALES</TH></tr>";
echo "</table>";
echo "</form></body></html>";
?>