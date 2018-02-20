<?php
require("conexion.inc");
require("estilos_inicio_adm.inc");
require("funcion_nombres.php");
$codCiclo=4;
$codGestion=1009;

echo "<center><h2>Reporte de Verificacion Distribucion Grupos Especiales<br>Ciclo: $codCiclo Gestion: $codGestion</h2></center><br>";

echo "<table border='1' cellspacing='0' align='center' class='texto'>";

$sqlGrupo="select g.`codigo_grupo_especial`, g.`nombre_grupo_especial`,
		(select c.descripcion from ciudades c where c.cod_ciudad=g.agencia) as agencia, g.codigo_linea
		from `grupo_especial` g order by g.codigo_linea";
$respGrupo=mysql_query($sqlGrupo);
while($datGrupo=mysql_fetch_array($respGrupo)){
	$codGrupo=$datGrupo[0];
	$nombreGrupo=$datGrupo[1];
	$nombreCiudad=$datGrupo[2];
	$codLinea=$datGrupo[3];
	
	echo "<tr><td colspan='7'><span style='color:#5858FA; background-color:#F3F781'>$nombreGrupo ------- $nombreCiudad ------ $codLinea-----$codGrupo</span></td></tr>";
	
	echo "<tr><td>Cod Med</td><td>Medico</td><td>Especialidad</td><td>Visitador Asignado</td><td>Nro. Parrillas</td><td>Observación</td></tr>";
		
	$sqlMedicos="select g.`cod_med`, 
		(select concat(m.ap_pat_med,' ',m.ap_mat_med,' ',m.nom_med) from medicos m where m.cod_med=g.cod_med)  as medico,
		(select c.`cod_especialidad` from `categorias_lineas` c where c.`cod_med`=g.cod_med and c.`codigo_linea`=$codLinea limit 0,1) as especialidad
		from `grupo_especial_detalle` g where g.`codigo_grupo_especial`=$codGrupo";

	$respMedicos=mysql_query($sqlMedicos);
	while($datMedicos=mysql_fetch_array($respMedicos)){
		$codMed=$datMedicos[0];
		$nombreMed=$datMedicos[1];
		$codEspecialidad=$datMedicos[2];
		
		$codVisitador=0;
		//sacamos el visitador asociado al medico
		
		$sqlLineaVisita="select count(*) from `lineas_visita` lv, `lineas_visita_especialidad` lve 
			where lv.`codigo_l_visita`=lve.`codigo_l_visita` and lve.`cod_especialidad`='$codEspecialidad' and lv.codigo_linea='$codLinea'";
		$respLineaVisita=mysql_query($sqlLineaVisita);
		$numLineaVisita=mysql_result($respLineaVisita,0,0);
		if($numLineaVisita==0){
			$sqlVisitador="select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
			`rutero_maestro_detalle_aprobado` rd where 
			rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
			rc.`codigo_ciclo`='$codCiclo' and rc.`codigo_gestion`='$codGestion' and 
			rd.`cod_med`='$codMed' and rc.codigo_linea='$codLinea'";
			
			//echo $sqlVisitador;
			
			$respVisitador=mysql_query($sqlVisitador);
			$numFilasVisitador=mysql_num_rows($respVisitador);
			if($numFilasVisitador>0){
				$codVisitador=mysql_result($respVisitador,0,0);
			}
		
		}else{
			$sqlVisitador="select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
			`rutero_maestro_detalle_aprobado` rd where 
			rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
			rc.`codigo_ciclo`='$codCiclo' and rc.`codigo_gestion`='$codGestion' and 
			rd.`cod_med`='$codMed' and rc.codigo_linea='$codLinea'";
			
			//echo $sqlVisitador;
			
			$respVisitador=mysql_query($sqlVisitador);
			
			while($datVisitador=mysql_fetch_array($respVisitador)){
				$codigoVis=$datVisitador[0];
				$sqlLinVisita="select gl.`cod_l_visita` from `grupo_especial` g, `grupoespecial_lineavisita` gl, lineas_visita_especialidad le where 
					g.`codigo_grupo_especial`=gl.`cod_grupo` and g.`codigo_grupo_especial`='$codGrupo' and g.codigo_linea='$codLinea' and 
					gl.cod_l_visita=le.codigo_l_visita and le.cod_especialidad='$codEspecialidad'";
				$respLinVisita=mysql_query($sqlLinVisita);
				while($datLinVisita=mysql_fetch_array($respLinVisita)){
					$codigoLineaVisitaGrupo=$datLinVisita[0];
					//verificamos que el visitador este en la linea de visita
					$sqlVeriFuncionario="select count(*) from `lineas_visita_visitadores` l, lineas_visita lv where 
					l.codigo_l_visita=lv.codigo_l_visita and lv.codigo_linea='$codLinea' and
					l.`codigo_funcionario`='$codigoVis' 
					and l.`codigo_l_visita`='$codigoLineaVisitaGrupo'";
					
					//echo $sqlVeriFuncionario;
					
					$respVeriFuncionario=mysql_query($sqlVeriFuncionario);
					$numFilasVeriFuncionario=mysql_result($respVeriFuncionario,0,0);
					if($numFilasVeriFuncionario==1){
						$codVisitador=$codigoVis;
					}
				}
			}
		}
		$nombreVisitador="----";
		if($codVisitador!=0){
			$nombreVisitador=nombreCompletoVisitador($codVisitador);
		}
		
		$sqlParrilla="select count(*) from `parrilla_especial` p where 
			p.`cod_ciclo`=$codCiclo and p.`codigo_gestion`=$codGestion and 
			p.`codigo_grupo_especial`=$codGrupo";
		$respParrilla=mysql_query($sqlParrilla);
		$nroParrillas=mysql_result($respParrilla,0,0);
		
		$obs="";		
		if($nroParrillas==0 || $codVisitador==0){
			$obs="<img src='imagenes/no.png'>";
		}else{
			$obs="<img src='imagenes/si.gif'>";
		}
		
		echo "<tr><td>$codMed</td>
			<td>$nombreMed</td>
			<td>$codEspecialidad</td>
			<td>$nombreVisitador</td>
			<td>$nroParrillas</td><td>$obs</td></tr>";
		
	}
}
echo "</table>";
?>