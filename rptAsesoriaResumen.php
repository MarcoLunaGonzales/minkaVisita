<script language='JavaScript'>
function totales(){
   var main=document.getElementById('main');   
   var numFilas=main.rows.length;
   var numCols=main.rows[1].cells.length;
	 
	 for(var j=2; j<=numCols-1; j++){
		alert(main.rows[0].cells[j].innerHTML);
		var subtotal=0;
	 		for(var i=1; i<=numFilas-2; i++){
					var dato=parseInt(main.rows[i].cells[j].innerHTML);
	 				subtotal=subtotal+dato;
	 		}
	 		var fila=document.createElement('TH');
			main.rows[numFilas-1].appendChild(fila);
			main.rows[numFilas-1].cells[j].innerHTML=subtotal;			
	 }	 
}
</script>

<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	echo "<html><body>";
	
	$codVisitador=$_GET['rpt_visitador'];
	$codCiclo=$_GET['rpt_ciclo'];
	$codGestion=$_GET['rpt_gestion'];
	$rpt_territorio=$_GET['rpt_territorio'];
	
	$vectorVisitador=explode(",",$codVisitador);
	//$nombreVisitador=$_GET['nombreVisitador'];
	/*
	DATOS DE LA REGIONAL
	*/
	
	$sql_gestion = mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo'");
	$dat_gestion = mysql_fetch_array($sql_gestion);
	$codigo_gestion = $dat_gestion[0];
	$nombreGestion = $dat_gestion[1];

	$fechaActual=time();
	$fechaActual=date("d/m/Y", $fechaActual);
	
	echo "<center><table border='0' class='textotit' width='80%'><tr><th colspan='2'>Asesoria en Consultorio</th>
	<tr><th align='left'>Gestion: $nombreGestion</th><th align='left'>Ciclo: $codCiclo</th></tr>
	</tr></table></center><br>";

	echo "<center><table border='1' class='texto' cellspacing='0' width='90%' id='main'>";
	echo "<tr><th>Nro.</th><th>Area Evaluación</th><th>Pts.</th>";
	for($i=0; $i<sizeof($vectorVisitador); $i++){
		$codVisitador=$vectorVisitador[$i];
		$sql = "select concat(paterno, ' ',nombres), cod_ciudad from funcionarios where codigo_funcionario=$codVisitador";
		$resp = mysql_query($sql);
		$dat = mysql_fetch_array($resp);
		$nombreVisitadorX = $dat[0];
		$codCiudadX=$dat[1];
		$sql = "select descripcion from ciudades where cod_ciudad=$codCiudadX";
		$resp = mysql_query($sql);
		$dat = mysql_fetch_array($resp);
		$globalAgencia = $dat[0];
		
		$sqlNumMed="select IFNULL(count(ad.cod_med),0) from asesorias a, asesorias_detalle ad
			where a.cod_asesoria=ad.cod_asesoria and a.cod_gestion=$codigo_gestion and a.cod_ciclo=$codCiclo and a.cod_visitador=$codVisitador";
		$respNumMed=mysql_query($sqlNumMed);
		$numeroMedicos=mysql_result($respNumMed,0,0);


		echo "<th>$nombreVisitadorX ($globalAgencia) #Med:$numeroMedicos</th>";
	}
	echo "</tr>";
	
	$sql1="select cod_pregunta, nombre_pregunta, puntaje from preguntas_evaluacion order by 1";
	$resp1=mysql_query($sql1);
	$sumaTotal=0;
	while($dat1=mysql_fetch_array($resp1)){	
		$codPregunta=$dat1[0];
		$nombrePregunta=$dat1[1];
		$puntaje=$dat1[2];
		
		$sumaPregunta=0;
		
		echo "<tr><td align='center'>$codPregunta</td>
		<td align='left'>$nombrePregunta</td>
		<td align='center'>$puntaje</td>";
		
		for($i=0; $i<sizeof($vectorVisitador); $i++){
			$codVisitador=$vectorVisitador[$i];
			
			$sqlPuntos="select IFNULL(sum(ae.puntaje),0) from asesorias a, asesorias_detalle ad, asesorias_evaluacion ae
				where a.cod_asesoria=ad.cod_asesoria and a.cod_gestion=$codigo_gestion and a.cod_ciclo=$codCiclo and a.cod_visitador=$codVisitador and 
				a.cod_asesoria=ae.cod_asesoria and ae.cod_pregunta=$codPregunta and ad.cod_asesoria=ae.cod_asesoria and ad.cod_med=ae.cod_med";
			$respPuntos=mysql_query($sqlPuntos);
			$puntosPregunta=mysql_result($respPuntos,0,0);
			
			echo "<td align='center'>$puntosPregunta</td>";
		}
		echo "</tr>";
	}
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><TH>TOTALES</TH>";
	
	for($i=0; $i<sizeof($vectorVisitador); $i++){
		$codVisitador=$vectorVisitador[$i];
		$sqlPuntos="select IFNULL(sum(ae.puntaje),0) from asesorias a, asesorias_detalle ad, asesorias_evaluacion ae
			where a.cod_asesoria=ad.cod_asesoria and a.cod_gestion=$codigo_gestion and a.cod_ciclo=$codCiclo and a.cod_visitador=$codVisitador and 
			a.cod_asesoria=ae.cod_asesoria and ad.cod_asesoria=ae.cod_asesoria and ad.cod_med=ae.cod_med";
		$respPuntos=mysql_query($sqlPuntos);
		$puntosPregunta=mysql_result($respPuntos,0,0);
		
		$sqlNumMed="select IFNULL(count(ad.cod_med),0) from asesorias a, asesorias_detalle ad
			where a.cod_asesoria=ad.cod_asesoria and a.cod_gestion=$codigo_gestion and a.cod_ciclo=$codCiclo and a.cod_visitador=$codVisitador";
		$respNumMed=mysql_query($sqlNumMed);
		$numeroMedicos=mysql_result($respNumMed,0,0);
		
		$nota=($puntosPregunta/(25*$numeroMedicos))*100;
		$nota=round($nota);
		echo "<th>$nota %</th>";
	}
	echo "</tr></table>";
	echo "</form></body></html>";
?>