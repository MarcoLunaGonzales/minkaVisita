<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");

$rpt_visitador  = $rpt_visitador;
$rpt_gestion    = $rpt_gestion;
$rpt_ciclo      = $rpt_ciclo;
$rpt_territorio = $rpt_territorio;
$rpt_dia        = $rpt_dia;
$rpt_nombreDia  = $rpt_nombreDia;
$nombreGestion  = nombreGestion($rpt_gestion);

echo "<table align='center' class='textotit'><tr><th>Productos Visitados<br>
			Gestion: $nombreGestion Ciclo: $rpt_ciclo
			Dia Contacto: $rpt_nombreDia</th></tr></table><br>";			

if($rpt_ver==1){
	echo "<table border='0' class='textomini' align='center'>
	<tr><td>Leyenda:</td><td>No entregado</td><td bgcolor='#ff0000' width='30%'></td>
	<tr><td></td><td>Entrego mas</td><td bgcolor='#00ff00' width='30%'></td>
	<tr><td></td><td>Entrego menos</td><td bgcolor='#ffff00' width='30%'></td>
	<tr><td></td><td>Producto Extra</td><td bgcolor='#ff00ff' width='30%'></td>
	</tr></table><br>";

	
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Dia Planificado</th><th>Visitador</th><th>Medico</th><th>Especialidad</th>
			<th>Categoria</th><th>Productos Visitados</th></tr>";
		
	$sql="SELECT o.dia_contacto, concat(m.ap_pat_med, ' ',m.ap_mat_med, ' ',m.nom_med) as medico, concat(f.paterno, ' ',f.nombres) as visitador, r.cod_espe, r.cod_cat, r.fecha_visita, r.cod_reg_visita, r.cod_dia_contacto, r.cod_med, r.cod_parrilla from reg_visita_cab r, medicos m, orden_dias o, funcionarios f where r.cod_med=m.cod_med and r.cod_dia_contacto=o.id and r.cod_gestion=$rpt_gestion and f.codigo_funcionario=r.cod_visitador and r.cod_ciclo=$rpt_ciclo and r.cod_visitador in ($rpt_visitador) and r.cod_dia_contacto in ($rpt_dia) order by visitador, id ,medico";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp)){
		$diaContacto=$dat[0];
		$nombreMed=$dat[1];
		$nombreVis=$dat[2];
		$codEspe=$dat[3];
		$codCat=$dat[4];
		$fechaVisita=$dat[5];
		$codRegistro=$dat[6];
		$codDia=$dat[7];
		$codMed=$dat[8];
		$codParrilla=$dat[9];		
		
		$sqlParrilla="SELECT m.codigo ,m.descripcion, pd.cantidad_muestra, ma.codigo_material, ma.descripcion_material, pd.cantidad_material from parrilla p, parrilla_detalle pd, muestras_medicas m, material_apoyo ma where p.codigo_parrilla = pd.codigo_parrilla and p.codigo_parrilla = 59571 and pd.codigo_muestra = m.codigo and pd.codigo_material = ma.codigo_material";
		$respDetalle=mysql_query($sqlParrilla);
		$cadDetalle="<table border='1' class='textomini'><tr><th>MM</th><th>Cant.</th><th>MA</th><th>Cant.</th></tr>";
		while($datDetalle=mysql_fetch_array($respDetalle)){
				$codMuestra=$datDetalle[0];
				$muestra=$datDetalle[1];
				$cantMuestra=$datDetalle[2];
				$material=$datDetalle[4];
				$cantMaterial=$datDetalle[5];
				
				$sqlEnt="SELECT m.descripcion, rd.CANT_MM_ENT + rd.CANT_MM_EXTENT, ma.descripcion_material, rd.CANT_MAT_ENT + rd.CANT_MAT_EXTENT from reg_visita_detalle rd, muestras_medicas m, material_apoyo ma where rd.COD_REG_VISITA = '$codRegistro' and rd.COD_MUESTRA = m.codigo and rd.COD_MATERIAL = ma.codigo_material and rd.COD_MUESTRA='$codMuestra'";
				$respEnt=mysql_query($sqlEnt);
				$numFilasEnt=mysql_num_rows($respEnt);
				$color="";
				if($numFilasEnt==0){
					$color="#ff0000";
				}else{
					$cantEnt=mysql_result($respEnt,0,1);
					if($cantEnt < $cantMuestra){
						$color="#ffff00";
					}
					if($cantEnt > $cantMuestra){
						$color="#00ff00";
					}
				}
				
				$cadDetalle.="<tr><td bgcolor='$color'>$muestra</td><td>$cantMuestra</td><td>$material</td><td>$cantMaterial</td></tr>";
		}
		$cadDetalle.="</table>";
		

		echo "<tr><td>$diaContacto</td><td>$nombreVis</td><td>$nombreMed</td>
		<td>$codEspe</td><td>$codCat</td>
		<td>$cadDetalle</td></tr>";
	}
	echo "</table>";
}
if($rpt_ver==0){
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Visitador</th><th>Medico</th><th>Especialidad</th><th>Categoria</th><th>Nro. Visitas</th>
			<th>MM Entregadas</th><th>MA Entregado</th></tr>";
		
	$sql="SELECT count(m.cod_med) as nroVisitas, concat(m.ap_pat_med, ' ',m.ap_mat_med, ' ',m.nom_med) as medico, concat(f.paterno, ' ',f.nombres) as visitador, r.cod_espe, r.cod_cat, r.cod_visitador, r.cod_med from reg_visita_cab r, medicos m, orden_dias o, funcionarios f where r.cod_med=m.cod_med and r.cod_dia_contacto=o.id and r.cod_gestion=$rpt_gestion and f.codigo_funcionario=r.cod_visitador and r.cod_ciclo=$rpt_ciclo and r.cod_visitador in ($rpt_visitador) and r.cod_dia_contacto in ($rpt_dia) group by visitador, medico order by visitador, nroVisitas desc, medico;";
	$resp=mysql_query($sql);

	while($dat=mysql_fetch_array($resp)){
		$nroVisitas=$dat[0];
		$nombreMed=$dat[1];
		$nombreVis=$dat[2];
		$codEspe=$dat[3];
		$codCat=$dat[4];
		$codVisitador=$dat[5];
		$codMed=$dat[6];
		
		$sqlDetalle="SELECT m.descripcion, SUM(rd.CANT_MM_ENT), sum(rd.CANT_MM_EXTENT) from reg_visita_cab r,reg_visita_detalle rd, muestras_medicas m where r.COD_REG_VISITA=rd.COD_REG_VISITA and r.COD_CICLO=$rpt_ciclo and r.COD_GESTION=$rpt_gestion and r.COD_VISITADOR=$codVisitador and r.COD_MED=$codMed and rd.COD_MUESTRA = m.codigo group by m.descripcion";
		$respDetalle=mysql_query($sqlDetalle);
		$cadDetalle="<table border='1' class='textomini' width='100%'><tr><th>MM</th><th>Cant.</th></tr>";
		while($datDetalle=mysql_fetch_array($respDetalle)){
				$muestra=$datDetalle[0];
				$cantMuestra=$datDetalle[1]+$datDetalle[2];
				$cadDetalle.="<tr><td>$muestra</td><td>$cantMuestra</td></tr>";
		}
		$cadDetalle.="</table>";

		$sqlDetalle="SELECT m.descripcion_material, SUM(rd.CANT_MAT_ENT), sum(rd.CANT_MAT_EXTENT) from reg_visita_cab r,reg_visita_detalle rd, material_apoyo m where r.COD_REG_VISITA=rd.COD_REG_VISITA and r.COD_CICLO=$rpt_ciclo and r.COD_GESTION=$rpt_gestion and r.COD_VISITADOR=$codVisitador and r.COD_MED=$codMed and rd.COD_MATERIAL = m.codigo_material and rd.cod_material<>0 group by m.descripcion_material";

		$respDetalle=mysql_query($sqlDetalle);
		$cadDetalle1="<table border='1' class='textomini' width='100%'><tr><th>MA</th><th>Cant.</th></tr>";
		while($datDetalle=mysql_fetch_array($respDetalle)){
				$muestra=$datDetalle[0];
				$cantMuestra=$datDetalle[1]+$datDetalle[2];
				$cadDetalle1.="<tr><td>$muestra</td><td>$cantMuestra</td></tr>";
		}
		$cadDetalle1.="</table>";

				
		echo "<tr><td>$nombreVis</td><td>$nombreMed</td><td>$codEspe</td><td>$codCat</td><td>$nroVisitas</td>
		<td>$cadDetalle</td><td>$cadDetalle1</td></tr>";
	}
	echo "</table>";
	
}
if($rpt_ver==2){
	echo "<table class='texto' border='1' cellspacing='0' cellpading='0' align='center'>
			<tr><th>Territorio</th><th>Visitador</th>
			<th>MM Entregadas</th><th>MA Entregado</th></tr>";
		
	$sqlVis="SELECT f.codigo_funcionario, c.descripcion, concat(f.paterno,' ',f.nombres) from funcionarios f, ciudades c where f.cod_ciudad=c.cod_ciudad and f.estado=1 and f.codigo_funcionario in ($rpt_visitador)";
	$respVis=mysql_query($sqlVis);
	while($datVis=mysql_fetch_array($respVis)){
		$codVisitador=$datVis[0];
		$nombreCiudad=$datVis[1];
		$nombreVis=$datVis[2];

		$sql="SELECT r.COD_VISITADOR, concat(m.descripcion,' ',m.presentacion), sum(rd.CANT_MM_ENT), sum(rd.CANT_MM_EXTENT), m.codigo from reg_visita_cab r, reg_visita_detalle rd, muestras_medicas m where r.COD_REG_VISITA = rd.COD_REG_VISITA and rd.COD_MUESTRA = m.codigo AND r.COD_CICLO='$rpt_ciclo' and r.COD_GESTION = '$rpt_gestion' and r.COD_DIA_CONTACTO in ($rpt_dia) and r.COD_VISITADOR in ($codVisitador) group by r.cod_visitador, rd.COD_MUESTRA order by m.descripcion";
		$resp=mysql_query($sql);
		$cadDetalle="<table border='1' class='textomini' width='100%'><tr><th>MM</th><th>Cant. Plan</th><th>Cant. Rec</th><th>Cant. Ent</th></tr>";
		while($dat=mysql_fetch_array($resp)){
			$codVisitador=$dat[0];
			$nombreMuestra=$dat[1];
			$cantidadMM=$dat[2];
			$cantidadMMExtra=$dat[3];
			$codMM=$dat[4];
			$cantMuestra=$cantidadMM+$cantidadMMExtra;
			//cantidad recibida almacen // cantidad planificada
			$sqlRec="SELECT sum(d.cantidad_planificada), sum(d.cantidad_sacadaalmacen) from distribucion_productos_visitadores d where d.cod_ciclo='$rpt_ciclo' and d.codigo_gestion='$rpt_gestion' and d.cod_visitador='$codVisitador' and d.codigo_producto='$codMM'";
			$respRec=mysql_query($sqlRec);
			$cantPlanificada=mysql_result($respRec,0,0);
			$cantRecibida=mysql_result($respRec,0,1);
			//
			$cadDetalle.="<tr><td>$nombreMuestra</td><td>$cantPlanificada</td><td>$cantRecibida</td><td>$cantMuestra</td></tr>";
		}
		$cadDetalle.="</table>";	
		
		$sql="SELECT r.COD_VISITADOR, m.descripcion_material, sum(rd.CANT_MAT_ENT), sum(rd.CANT_MAT_EXTENT), m.codigo_material from reg_visita_cab r, reg_visita_detalle rd, material_apoyo m where r.COD_REG_VISITA = rd.COD_REG_VISITA and rd.COD_MATERIAL = m.codigo_material AND r.COD_CICLO = '$rpt_ciclo' and r.COD_GESTION = '$rpt_gestion' and r.COD_DIA_CONTACTO in ($rpt_dia) and r.COD_VISITADOR in ($rpt_visitador) and m.codigo_material<>0 group by r.cod_visitador, rd.COD_MATERIAL order by m.descripcion_material";
		$resp=mysql_query($sql);
		$cadDetalleMA="<table border='1' class='textomini' width='100%'><tr><th>MA</th><th>Cant. Plan</th><th>Cant. Rec</th><th>Cant. Ent</th></tr>";
		while($dat=mysql_fetch_array($resp)){
			$codVisitador=$dat[0];
			$nombreMuestra=$dat[1];
			$cantidadMM=$dat[2];
			$cantidadMMExtra=$dat[3];
			$codMaterial=$dat[4];
			$cantMuestra=$cantidadMM+$cantidadMMExtra;
			//cantidad recibida almacen // cantidad planificada
			$sqlRec="SELECT sum(d.cantidad_planificada), sum(d.cantidad_sacadaalmacen) from distribucion_productos_visitadores d where d.cod_ciclo='$rpt_ciclo' and d.codigo_gestion='$rpt_gestion' and d.cod_visitador='$codVisitador' and d.codigo_producto='$codMaterial'";
			$respRec=mysql_query($sqlRec);
			$cantPlanificada=mysql_result($respRec,0,0);
			$cantRecibida=mysql_result($respRec,0,1);
			//
			$cadDetalleMA.="<tr><td>$nombreMuestra</td><td>$cantPlanificada</td><td>$cantRecibida</td><td>$cantMuestra</td></tr>";
		}
		$cadDetalleMA.="</table>";	
		
		
		echo "<tr><td>$nombreCiudad</td><td>$nombreVis</td><td>$cadDetalle</td><td>$cadDetalleMA</td></tr>";
	}
	 

}



?>