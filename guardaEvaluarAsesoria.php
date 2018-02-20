<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	
	$codAsesoria=$_GET['codAsesoria'];
	$codMed=$_GET['codMed'];
	$observaciones=$_GET['observaciones'];
	//echo $codAsesoria." ".$codMed."<br>"; 
	
	$sqlUpd="update asesorias_detalle set observaciones='$observaciones' where cod_asesoria='$codAsesoria' and cod_med='$codMed'";
	$respUpd=mysql_query($sqlUpd);
	
	$sql1="select cod_pregunta, nombre_pregunta, puntaje from preguntas_evaluacion order by 1";
	$resp1=mysql_query($sql1);
	while($dat1=mysql_fetch_array($resp1))
	{
		$codPregunta=$dat1[0];
		$valorPregunta=$_GET['pregunta'.$codPregunta];
		//echo "$codPregunta $codigoPregunta<br>";
		
		$sqlInsert="insert into asesorias_evaluacion values($codAsesoria, $codMed, $codPregunta, $valorPregunta)";
		//echo $sqlInsert;
		$respInsert=mysql_query($sqlInsert);
	}

	echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='listadoVisitadoresAsesoria.php';
			</script>";


?>