<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcionFechasCiclo.php");
require("funcion_nombres.php");

$rpt_gestion=$rpt_gestion;
$rpt_ciclo=$rpt_ciclo;
$rpt_territorio=$rpt_territorio;
$rpt_visitador=$rpt_visitador;

$nombreGestion=nombreGestion($rpt_gestion);
$nombreVisitador=nombreVisitador($rpt_visitador);

echo "<table align='center' class='textotit'><tr><th>Secuencia de Visita x Medico<br>
			Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
			</table><br>";

$sqlLeyenda="select codigo_funcionario, concat(paterno,' ',nombres) from funcionarios where codigo_funcionario in ($rpt_visitador)";
$respLeyenda=mysql_query($sqlLeyenda);
echo "<table border='0' class='textomini' align='center'>";
echo "<tr><td>Leyenda:</td><th>Codigo</th><th>Visitador</th></td>";
while($datLeyenda=mysql_fetch_array($respLeyenda)){
	$codFuncionario=$datLeyenda[0];
	$nombreFuncionario=$datLeyenda[1];
	echo "<tr><td></td><th>$codFuncionario</th><th align='left'>$nombreFuncionario</th></td>";
}
echo "</table>";


echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th>";

$sqlDias="select id, dia_contacto from orden_dias order by 1";
$respDias=mysql_query($sqlDias);
$colorborde="#ffeeee";
while($datDias=mysql_fetch_array($respDias)){
	$codDia=$datDias[0];
	$nombreDia=$datDias[1];
	if($codDia>=1 && $codDia<=5){ $colorborde="#0101DF";		}
	if($codDia>=6 && $codDia<=10){ $colorborde="#FF0000";		}
	if($codDia>=11 && $codDia<=15){ $colorborde="#FFFF00";		}
	if($codDia>=16 && $codDia<=20){ $colorborde="#00FF00";		}
	echo "<th bordercolor='$colorborde'>$nombreDia</th>";
}
echo "</tr>";
	
$sql="select count(rd.cod_med), concat(m.`ap_pat_med`, ' ', m.`nom_med`), rd.`cod_med`, 
			rd.`cod_especialidad`, rd.`categoria_med` from `rutero_maestro_cab_aprobado` rc, 
			`rutero_maestro_aprobado` r, `rutero_maestro_detalle_aprobado` rd, medicos m 
			where rc.`codigo_ciclo`=$rpt_ciclo and  rc.`codigo_gestion` = $rpt_gestion and rd.cod_med = m.cod_med and 
			r.`cod_visitador` in ($rpt_visitador) and r.`cod_contacto` = rd.`cod_contacto` 
			and rc.`cod_rutero`=r.`cod_rutero` group by rd.cod_med order by 5, 2";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$frecuencia=$dat[0];
	$nombreMed=$dat[1];
	$codMed=$dat[2];
	$codEspe=$dat[3];
	$codCat=$dat[4];
	
	$sqlSec="select rd.`cod_visitador`, o.id from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` r, 
			`rutero_maestro_detalle_aprobado` rd, `orden_dias` o where rc.`cod_rutero` = r.`cod_rutero` and 
			r.`dia_contacto`= o.`dia_contacto` and r.`cod_contacto` = rd.`cod_contacto` and 
			rc.`cod_visitador` in ($rpt_visitador) and rc.`codigo_ciclo` = $rpt_ciclo and rc.`codigo_gestion`=$rpt_gestion and 
			rd.`cod_med` = $codMed";
  $respSec=mysql_query($sqlSec);
	$diasSecuencia[][]=array();	
	$j=0;
	while($datSec=mysql_fetch_array($respSec)){
		$codVisitador=$datSec[0];
		$diaContacto=$datSec[1];
		
		$diasSecuencia[$j][1]=$codVisitador;
		$diasSecuencia[$j][2]=$diaContacto;
		
		$j++;
	}

    
	echo "<tr><td>$nombreMed</td><td>$codEspe</td><td>$codCat</td><td>$frecuencia</td>";
	$respDias=mysql_query($sqlDias);
	
	while($datDias=mysql_fetch_array($respDias)){
		$codDia=$datDias[0];
		$cadVis="";
		$indice=0;
		for($i=0;$i<=$j-1;$i++){
			if($codDia==$diasSecuencia[$i][2]){
				$cadVis.=$diasSecuencia[$i][1]." - ";
				$indice++;
			}
		}	
		if($indice>1){
			$color="#ff0000";
		}else{
			$color="";
		}
		if($codDia>=1 && $codDia<=5){ $colorborde="#0101DF";		}
		if($codDia>=6 && $codDia<=10){ $colorborde="#FF0000";		}
		if($codDia>=11 && $codDia<=15){ $colorborde="#FFFF00";		}
		if($codDia>=16 && $codDia<=20){ $colorborde="#00FF00";		}

		echo "<td bgcolor='$color' bordercolor='$colorborde'>&nbsp; $cadVis</td>";		
	}
	unset($diasSecuencia);		
	echo "</tr>";
	

}
echo "</table>";
?>