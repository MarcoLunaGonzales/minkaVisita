<?php

	require("conexion.inc");
	require("estilos_regional_pri.inc");
	
	/*$sql = "select descripcion from ciudades where cod_ciudad=$global_agencia";
	$resp = mysql_query($sql);
	$dat = mysql_fetch_array($resp);
	$globalAgencia = $dat[0];*/
	
	$sql_gestion = mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo'");
	$dat_gestion = mysql_fetch_array($sql_gestion);
	$codGestion = $dat_gestion[0];
	$nombreGestion= $dat_gestion[1];
	
	$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo'");
	$dat_ciclo = mysql_fetch_array($sql_ciclo);
	$codCiclo = $dat_ciclo[0];
	
	
	/*if($global_usuario==1256 || $global_usuario==1365){
		$codGestion=1013;
		$codCiclo=11;
		$nombreGestion="2016-2017";
	}*/
	
	echo "<form method='post' action=''>";

	echo "<h2 align='center'>Planificación de Asesorias por Ciclo<br>Ciclo: $codCiclo</h2>";
	$sql="select id, dia_contacto from orden_dias";
	$resp=mysql_query($sql);
	
	echo "<table border='1' cellspacing='0' cellpadding='0' align='center'>";
	while($dat=mysql_fetch_array($resp)){
		$id=$dat[0];
		$diaContacto=$dat[1];

		if($id==(($id%5)+1)){
			echo "<tr>";
		}
		
		$sqlVisi="select f.codigo_funcionario, concat(f.paterno,' ', f.nombres), cod_asesoria from asesorias a, funcionarios f  
			where cod_gestion=$codGestion and cod_ciclo=$codCiclo and dia_contacto='$diaContacto' 
			and a.cod_visitador=f.codigo_funcionario and f.cod_ciudad in ($global_agencia) order by 2";
		$respVisi=mysql_query($sqlVisi);
		$cadenaVis="";
		while($datVisi=mysql_fetch_array($respVisi)){
			$codVis=$datVisi[0];
			$nombreVisi=$datVisi[1];
			$codAsesoria=$datVisi[2];
			$cadenaVis=$cadenaVis." <a href='detalleAsesoriaVisitador.php?codVisitador=$codVis&codAsesoria=$codAsesoria&nombreVisitador=$nombreVisi&diaContacto=$diaContacto&codGestion=$codGestion&codCiclo=$codCiclo'>".$nombreVisi."</a><br>";
			
		}
		
		echo "<td width='200px' height='120px' valign='top'>
		<table border='0' width='100%'>
		<tr><td bgcolor='#FFFF00' align='center'>
		<a href='registrarAsesoriaPlani.php?codGestion=$codGestion&codCiclo=$codCiclo&diaContacto=$diaContacto'>
		<span style='font-size:20px;'>$diaContacto</span>
		</a>
		</td></tr>
		<tr><td>
		<span style='color:black; background-color:#2EFEF7;font-size:13px;'>$cadenaVis</span>
		</td></tr>
		</table>
		</td>";
		
		if(($id%5)==0){
			echo "</tr>";
		}
	}
	echo "</table>";
	echo "</form>";
	require("home_regional1.inc");
?>