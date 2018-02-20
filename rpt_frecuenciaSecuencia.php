<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcionFechasCiclo.php");
require("funcion_nombres.php");

$rpt_visitador=$rpt_visitador;
$rpt_gestion=$rpt_gestion;
$rpt_ciclo=$rpt_ciclo;
$rpt_territorio=$rpt_territorio;

$nombreGestion=nombreGestion($rpt_gestion);
$nombreVisitador=nombreVisitador($rpt_visitador);

echo "<table align='center' class='textotit'><tr><th>Frecuencia y Secuencia de Visita<br>
			Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
			Visitador: $nombreVisitador
			</table><br>";

echo "<table border='0' class='textomini' align='center'>
<tr><td>Leyenda:</td><td>Planificado</td><td bgcolor='#ff0000' width='30%'></td>
<tr><td></td><td>Ejecutado</td><td bgcolor='#ffff00' width='30%'></td>
</tr></table><br>";

echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Frecuencia</th>";

$sqlDias="select id, dia_contacto from orden_dias order by 1";
$respDias=mysql_query($sqlDias);
while($datDias=mysql_fetch_array($respDias)){
	$codDia=$datDias[0];
	$nombreDia=$datDias[1];
	echo "<th colspan=2>$nombreDia</th>";
}
echo "</tr>";
	
$sql="select count(rd.cod_med), concat(m.`ap_pat_med`,' ',m.`nom_med`),
	rd.`cod_med`, rd.`cod_especialidad`, rd.`categoria_med`
	from `rutero_utilizado` r, `rutero_detalle_utilizado` rd , medicos m where
	r.`cod_ciclo`='$rpt_ciclo' and r.`codigo_gestion`='$rpt_gestion' and rd.cod_med=m.cod_med and 
	r.`cod_visitador`='$rpt_visitador' and r.`cod_contacto`=rd.`cod_contacto` group by rd.cod_med order by
	5,2";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$frecuencia=$dat[0];
	$nombreMed=$dat[1];
	$codMed=$dat[2];
	$codEspe=$dat[3];
	$codCat=$dat[4];
	
	$sqlFrec="select o.id from `rutero_utilizado` r, `rutero_detalle_utilizado` rd, `orden_dias` o 
		where r.`cod_ciclo` = '$rpt_ciclo' and r.`codigo_gestion` = '$rpt_gestion' and o.`dia_contacto` = r.`dia_contacto` and       
		r.`cod_visitador` = '$rpt_visitador' and r.`cod_contacto` = rd.`cod_contacto` and rd.`cod_med` = '$codMed'";
	$respFrec=mysql_query($sqlFrec);
	$diasFrecuencia[]=array();	
	$j=0;
	while($datFrec=mysql_fetch_array($respFrec)){
		$diasFrecuencia[$j]=$datFrec[0];
		$j++;
	}
	
	$sqlSec="select r.`COD_DIA_CONTACTO`, r.`FECHA_VISITA`
		from `reg_visita_cab` r
		where r.`COD_CICLO` = '$rpt_ciclo' and
    r.`COD_GESTION` = '$rpt_gestion' and r.`COD_VISITADOR`='$rpt_visitador' and r.`COD_MED`='$codMed'";
  $respSec=mysql_query($sqlSec);
	$diasSecuencia[]=array();	
	$j=0;
	while($datSec=mysql_fetch_array($respSec)){
		$diaContacto=$datSec[0];
		$fechaSecuencia=$datSec[1];
		
		$diaVisita=devuelveDiaRegistro($fechaSecuencia);
		if($diaVisita==""){
			$diaVisita=$diaContacto;
		}
		
		$diasSecuencia[$j]=$diaVisita;
		$j++;
	}
  
    
	echo "<tr><td>$nombreMed</td><td>$codEspe</td><td>$codCat</td><td>$frecuencia</td>";
	$respDias=mysql_query($sqlDias);
	while($datDias=mysql_fetch_array($respDias)){
		$codDia=$datDias[0];
		$txtFrec="";
		$txtSec="";
		if(in_array($codDia,$diasFrecuencia)){
			$txtFrec="#ff0000";
		}
		if(in_array($codDia,$diasSecuencia)){
			$txtSec="#ffff00";
		}
		echo "<td bgcolor='$txtFrec'>&nbsp;</td>";		
		echo "<td bgcolor='$txtSec'>&nbsp;</td>";
	}
	unset($diasFrecuencia);		
	unset($diasSecuencia);		
	echo "</tr>";
	

}
echo "</table>";
?>