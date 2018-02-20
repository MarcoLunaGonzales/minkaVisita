<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcionFechasCiclo.php");
require("funcion_nombres.php");
$rpt_visitador=$rpt_visitador;
$rpt_gestion=$rpt_gestion;
$rpt_ciclo=$rpt_ciclo;
$rpt_territorio=$rpt_territorio;
$rpt_dia=$rpt_dia;
$rpt_nombreDia=$rpt_nombreDia;

$nombreGestion=nombreGestion($rpt_gestion);

echo "<table align='center' class='textotit'><tr><th>Contactos Realizados<br>
Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
Dia Contacto: $rpt_nombreDia</th></tr></table><br>";

if($rpt_ver==1){
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
	<tr><th>Dia Planificado</th><th>Visitador</th><th>Medico</th><th>Especialidad</th>
	<th>Categoria</th><th>Dia Realizado</th><th>Recuperado?</th></tr>";
	
	$sql="SELECT o.dia_contacto, concat(m.ap_pat_med, ' ',m.ap_mat_med, ' ',m.nom_med) as medico, concat(f.paterno, ' ',f.nombres) as visitador, r.cod_espe, r.cod_cat, r.fecha_visita from reg_visita_cab r, medicos m, orden_dias o, funcionarios f where r.cod_med=m.cod_med and r.cod_dia_contacto=o.id and r.cod_gestion=$rpt_gestion and f.codigo_funcionario=r.cod_visitador and r.cod_ciclo=$rpt_ciclo and r.cod_visitador in ($rpt_visitador) and r.cod_dia_contacto in ($rpt_dia) order by visitador, id ,medico";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
		$diaContacto=$dat[0];
		$nombreMed=$dat[1];
		$nombreVis=$dat[2];
		$codEspe=$dat[3];
		$codCat=$dat[4];
		$fechaVisita=$dat[5];
		$diaRegistro=devuelveDiaRegistro($fechaVisita);
		if($diaRegistro==""){
			$diaRegistro=$diaContacto;
		}
		$recuperado="0";
		if($diaRegistro!=$diaContacto){
			$recuperado=1;
		}
		echo "<tr><td>$diaContacto</td><td>$nombreVis</td><td>$nombreMed</td>
		<td>$codEspe</td><td>$codCat</td>
		<td>$diaRegistro</td><td>$recuperado</td></tr>";
	}
	echo "</table>";
}else{
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
	<tr><th>Visitador</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Nro. Visitas</th>
	<th>Contactos Planificado</th><th>Diferencia</th></tr>";
	
	$sql="SELECT count(m.cod_med) as nroVisitas, concat(m.ap_pat_med, ' ',m.ap_mat_med, ' ',m.nom_med) as medico, concat(f.paterno, ' ',f.nombres) as visitador, r.cod_espe, r.cod_cat, r.cod_med, r.cod_visitador from reg_visita_cab r, medicos m, orden_dias o, funcionarios f where r.cod_med=m.cod_med and r.cod_dia_contacto=o.id and r.cod_gestion=$rpt_gestion and f.codigo_funcionario=r.cod_visitador and r.cod_ciclo=$rpt_ciclo and r.cod_visitador in ($rpt_visitador) and r.cod_dia_contacto in ($rpt_dia) group by visitador, medico order by visitador, nroVisitas desc, medico;";
	$resp=mysql_query($sql);
	$nombreVisPiv=mysql_result($resp,0,2);
	$sumaVisitas=0;
	$sumaTotalVisitas=0;
	$sumaVisPlan=0;
	$sumaTotalVisPlan=0;
	$sumaDif=0;
	$sumaTotalDif=0;

	while($dat=mysql_fetch_array($resp)){
		$nroVisitas=$dat[0];
		$nombreMed=$dat[1];
		$nombreVis=$dat[2];
		$codEspe=$dat[3];
		$codCat=$dat[4];
		$codMed=$dat[5];
		$codVisitador=$dat[6];
		
		$sqlContPlan="SELECT count(*) from rutero r, rutero_detalle rd, orden_dias o where r.cod_ciclo='$rpt_ciclo' and r.codigo_gestion='$rpt_gestion' and r.cod_visitador='$codVisitador' and r.cod_contacto=rd.cod_contacto and r.dia_contacto=o.dia_contacto and rd.cod_med='$codMed' and o.id in ($rpt_dia)";
		$respContPlan=mysql_query($sqlContPlan);
		$numContPlan=mysql_result($respContPlan,0,0);
		$diferencia=$numContPlan-$nroVisitas;
		
		if($nombreVis!=$nombreVisPiv){
			echo "<tr><td colspan='4'>Subtotal</td><td>$sumaVisitas</td><td>$sumaVisPlan</td><td>$sumaDif</td></tr>";
			$sumaVisitas=0;
			$sumaVisPlan=0;
			$sumaDif=0;
			$nombreVisPiv=$nombreVis;
		}
		$sumaVisitas=$sumaVisitas+$nroVisitas;
		$sumaVisPlan=$sumaVisPlan+$numContPlan;
		$sumaDif=$sumaDif+$diferencia;
		
		$sumaTotalVisitas=$sumaTotalVisitas+$nroVisitas;
		$sumaTotalVisPlan=$sumaTotalVisPlan+$numContPlan;
		$sumaTotalDif=$sumaTotalDif+$diferencia;

		echo "<tr><td>$nombreVis</td><td>$nombreMed</td><td>$codEspe</td><td>$codCat</td>
		<td>$nroVisitas</td><td>$numContPlan</td><td>$diferencia</td></tr>";
	}
	echo "<tr><td colspan='4'>Subtotal</td><td>$sumaVisitas</td><td>$sumaVisPlan</td><td>$sumaDif</td></tr>";
	echo "<tr><td colspan='4'>TOTAL</td><td>$sumaTotalVisitas</td><td>$sumaTotalVisPlan</td><td>$sumaTotalDif</td></tr>";

	echo "</table>";
	
}
?>