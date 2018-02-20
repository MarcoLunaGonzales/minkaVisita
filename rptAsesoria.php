<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	
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

	for($i=0; $i<sizeof($vectorVisitador); $i++){
		
		$codVisitador=$vectorVisitador[$i];
		
		//cho $codVisitador;
		
		$sql = "select concat(paterno, ' ',materno, ' ',nombres), cod_ciudad from funcionarios where codigo_funcionario=$codVisitador";
		$resp = mysql_query($sql);
		$dat = mysql_fetch_array($resp);
		$nombreVisitadorX = $dat[0];
		$codCiudadX=$dat[1];
		$sql = "select descripcion from ciudades where cod_ciudad=$codCiudadX";
		$resp = mysql_query($sql);
		$dat = mysql_fetch_array($resp);
		$globalAgencia = $dat[0];

		echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
		echo "<tr><th>Nro.</th><th>($nombreVisitadorX - $globalAgencia) Area Evaluación</th><th>Pts.</th>";
		
		$sqlMed="select m.cod_med, concat(m.ap_pat_med,' ',m.nom_med)medico, a.cod_asesoria 
		from asesorias a, asesorias_detalle ad, medicos m 
		where a.cod_asesoria=ad.cod_asesoria and a.cod_visitador='$codVisitador' and a.cod_gestion='$codGestion' and a.cod_ciclo='$codCiclo' and 
		ad.cod_med=m.cod_med order by 2";
		//echo $sqlMed;
		$respMed=mysql_query($sqlMed);
		$numMedicos=0;
		while($datMed=mysql_fetch_array($respMed)){
			$codMed=$datMed[0];
			$nomMed=$datMed[1];
			$codAsesoria=$datMed[2];
			echo "<th>$nomMed</th>";
			$numMedicos++;
		}
		echo "<th>Total</th></tr>";
		
		$sql1="select cod_pregunta, nombre_pregunta, puntaje from preguntas_evaluacion order by 1";
		$resp1=mysql_query($sql1);
		$sumaTotal=0;
		while($dat1=mysql_fetch_array($resp1))
		{
			$codPregunta=$dat1[0];
			$nombrePregunta=$dat1[1];
			$puntaje=$dat1[2];
			
			$sumaPregunta=0;
			
			echo "<tr><td align='center'>$codPregunta</td>
			<td align='left'>$nombrePregunta</td>
			<td align='center'>$puntaje</td>";
			
			$sqlMed="select m.cod_med, concat(m.ap_pat_med,' ',m.nom_med)medico, a.cod_asesoria 
			from asesorias a, asesorias_detalle ad, medicos m 
			where a.cod_asesoria=ad.cod_asesoria and a.cod_visitador='$codVisitador' and a.cod_gestion='$codGestion' and a.cod_ciclo='$codCiclo' and 
			ad.cod_med=m.cod_med order by 2";
			$respMed=mysql_query($sqlMed);
			while($datMed=mysql_fetch_array($respMed)){
				$codMed=$datMed[0];
				$codAsesoriaX=$datMed[2];
				
				$sqlEva="select IFNULL(puntaje,0) from asesorias_evaluacion where cod_asesoria='$codAsesoriaX' and cod_med='$codMed' and cod_pregunta='$codPregunta'";
				//echo $sqlEva."<br>";
				$respEva=mysql_query($sqlEva);
				$filas=mysql_num_rows($respEva);
				$puntajePregunta=0;
				if($filas>0){
					$puntajePregunta=mysql_result($respEva,0,0);
				}
				
				$sumaPregunta=$sumaPregunta+$puntajePregunta;
				$sumaTotal=$sumaTotal+$puntajePregunta;
				
				echo "<td align='center'>$puntajePregunta</td>";
			}
			
			echo "<td align='center'>$sumaPregunta</td></tr>";

		}
		
		echo "<tr><th colspan='3'>Total</th>";
		$sqlMed="select m.cod_med, concat(m.ap_pat_med,' ',m.nom_med)medico, a.cod_asesoria 
			from asesorias a, asesorias_detalle ad, medicos m 
			where a.cod_asesoria=ad.cod_asesoria and a.cod_visitador='$codVisitador' and a.cod_gestion='$codGestion' and a.cod_ciclo='$codCiclo' and 
			ad.cod_med=m.cod_med order by 2";
		$respMed=mysql_query($sqlMed);
		$numRegX=mysql_num_rows($respMed);
			while($datMed=mysql_fetch_array($respMed)){
				echo "<td>-</td>";
			}
		
		if($numMedicos>0){
			$nota=($sumaTotal/(25*$numMedicos))*100;
		}else{
			$nota=0;
		}
		
		echo "<th>Nota $nota%</th></tr></table>";
	
	}
	

	
	/*echo "<br><br><center><table border='1' cellspacing='0' cellpadding='0' class='textotit' width='80%'>
	<tr><th align='left'>Observaciones y acciones sugeridas por el evaluador</th></tr>
	<tr><th>&nbsp;</th></tr>
	<tr><th>&nbsp;</th></tr>
	<tr><th align='left'>Compromisos del representante y plazos para mejorar áreas  observadas:</th></tr>
	<tr><th>&nbsp;</th></tr>
	<tr><th>&nbsp;</th></tr>
	</table></center><br>";*/
	
	echo "</form>";
?>