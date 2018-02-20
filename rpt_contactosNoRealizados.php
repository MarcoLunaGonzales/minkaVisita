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

echo "<table align='center' class='textotit'><tr><th>Contactos No Realizados<br>
			Gestion: $nombreGestion Ciclo: $rpt_ciclo<br>
			Dia Contacto: $rpt_nombreDia</th></tr></table><br>";

if($rpt_ver==1){
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Dia Planificado</th><th>Visitador</th><th>Medico</th><th>Especialidad</th><th>Categoria</th></tr>";
		
	$sql="select r.`dia_contacto`, concat(m.`ap_pat_med`, ' ',m.`nom_med`) as medico,
	 concat(f.paterno, ' ',f.nombres) as visitador,	rd.`cod_especialidad`, rd.`categoria_med` 
	 from `rutero_detalle` rd, `rutero` r, funcionarios f, `orden_dias` o, medicos m 
	 where r.`cod_contacto` = rd.`cod_contacto` and f.codigo_funcionario=r.cod_visitador and
		r.`cod_visitador` in ($rpt_visitador) and r.`cod_ciclo` = $rpt_ciclo and r.`codigo_gestion` = $rpt_gestion and 
		rd.`estado` = 0 and r.`dia_contacto` = o.`dia_contacto` and  rd.`cod_med`=m.`cod_med` and o.id in ($rpt_dia)
		order by o.`id`, rd.`categoria_med`";
	
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
		$diaContacto=$dat[0];
		$nombreMed=$dat[1];
		$nombreVis=$dat[2];
		$codEspe=$dat[3];
		$codCat=$dat[4];
		echo "<tr><td>$diaContacto</td><td>$nombreVis</td><td>$nombreMed</td>
		<td>$codEspe</td><td>$codCat</td>
		</tr>";
	}
	echo "</table>";
}else{
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Visitador</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Nro. Visitas</th>
			<th>Contactos Planificados</th></tr>";
		
	$sql="select count(rd.`cod_med`), rd.`cod_med`, concat(f.paterno,' ',f.nombres), 
	 		concat(m.`ap_pat_med`, ' ',m.`nom_med`), rd.`cod_especialidad`, rd.`categoria_med`, rd.cod_med, r.cod_visitador
	 		from `rutero_detalle` rd, `rutero` r, `orden_dias` o, medicos m, `funcionarios` f 
	 		where r.`cod_contacto` = rd.`cod_contacto` and 
	 		r.`cod_visitador` in ($rpt_visitador) and r.`cod_ciclo` = $rpt_ciclo and 
	 		f.`codigo_funcionario`=r.`cod_visitador` and r.`codigo_gestion` = $rpt_gestion and 
	 		rd.`estado` = 0 and r.`dia_contacto` = o.`dia_contacto` and rd.`cod_med`=m.`cod_med` and 
	 		o.`id` in ($rpt_dia) group by rd.`cod_med` order by 3,1 desc,6";
	$resp=mysql_query($sql);

	$nombreVisPiv=mysql_result($resp,0,2);
	$sumaVisitas=0;
	$sumaTotalVisitas=0;
	$sumaPlan=0;
	$sumaTotalPlan=0;
	while($dat=mysql_fetch_array($resp)){
		$nroVisitas=$dat[0];
		$nombreMed=$dat[3];
		$nombreVis=$dat[2];
		$codEspe=$dat[4];
		$codCat=$dat[5];
		$codMed=$dat[6];
		$codVisitador=$dat[7];
		
		$sqlContPlan="select count(*) from `rutero` r, `rutero_detalle` rd, `orden_dias` o where
		r.`cod_ciclo`='$rpt_ciclo' and r.`codigo_gestion`='$rpt_gestion' and  
		r.`cod_visitador`='$codVisitador' and r.`cod_contacto`=rd.`cod_contacto` and r.`dia_contacto`=o.`dia_contacto`
		and rd.`cod_med`='$codMed' and o.`id` in ($rpt_dia)";
		$respContPlan=mysql_query($sqlContPlan);
		$numContPlan=mysql_result($respContPlan,0,0);
		$diferencia=$numContPlan-$nroVisitas;

		
		if($nombreVis!=$nombreVisPiv){
			echo "<tr><td colspan='4'>Subtotal</td><td>$sumaVisitas</td><td>$sumaPlan</td></tr>";
			$sumaVisitas=0;
			$sumaPlan=0;
			$nombreVisPiv=$nombreVis;
		}
		$sumaVisitas=$sumaVisitas+$nroVisitas;
		$sumaTotalVisitas=$sumaTotalVisitas+$nroVisitas;
		$sumaPlan=$sumaPlan+$numContPlan;
		$sumaTotalPlan=$sumaTotalPlan+$numContPlan;

		echo "<tr><td>$nombreVis</td><td>$nombreMed</td><td>$codEspe</td><td>$codCat</td>
		<td>$nroVisitas</td><td>$numContPlan</td></tr>";

	}
		echo "<tr><td colspan='4'>Subtotal</td><td>$sumaVisitas</td><td>$sumaPlan</td></tr>";
		echo "<tr><td colspan='4'>TOTAL</td><td>$sumaTotalVisitas</td><td>$sumaTotalPlan</td></tr>";
	echo "</table>";
	
}
?>