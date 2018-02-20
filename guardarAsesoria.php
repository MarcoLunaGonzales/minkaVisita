<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	//codigo
	$sql="select IFNULL(max(cod_asesoria),0)+1 from asesorias";
	$resp=mysql_query($sql);
	$codAsesoria=mysql_result($resp,0,0);
	
	
	$codVisitador=$_GET['codVisitador'];
	$diaContacto=$_GET['diaContacto'];
	$codGestion=$_GET['codGestion'];
	$codCiclo=$_GET['codCiclo'];
	$datos=$_GET['datos'];
	
	$sqlInsert="insert into asesorias values($codAsesoria,$codVisitador, '$diaContacto',$codGestion, $codCiclo)";
	echo $sqlInsert."<br>";
	$respInsert=mysql_query($sqlInsert);
	
	$vector=explode(",",$datos);
	$n=sizeof($vector);
	for($i=0;$i<$n;$i++)
	{
		if($vector[$i]>0 && $vector[$i]!=""){
			$sqlInsert2="insert into asesorias_detalle values($codAsesoria, $vector[$i], '','')";
			echo $sqlInsert2."<br>";
			$respInsert2=mysql_query($sqlInsert2);
		}		
	}

	/*echo "<script language='Javascript'>
			alert('Los datos fueron guardados correctamente.');
			location.href='listadoVisitadoresAsesoria.php';
			</script>";*/


?>