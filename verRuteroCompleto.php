<?php

	require("conexion.inc");
	require("estilos_regional_pri.inc");
	
	
	$visitador=$_GET['codVisitador'];

	$sql_gestion = mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo'");
	$dat_gestion = mysql_fetch_array($sql_gestion);
	$codGestion = $dat_gestion[0];
	$nombreGestion= $dat_gestion[1];
	
	$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo'");
	$dat_ciclo = mysql_fetch_array($sql_ciclo);
	$codCiclo = $dat_ciclo[0];
	
	/*if($global_usuario==1085 || $global_usuario==0){
		$codGestion=1014;
		$codCiclo=1;
		$nombreGestion="2016-2017";
	}*/
	
	$sql_cab="select paterno,materno,nombres from funcionarios where codigo_funcionario='$visitador'";
	$resp_cab=mysql_query($sql_cab);
	$dat_cab=mysql_fetch_array($resp_cab);
	$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";
	echo "<form method='post' action='opciones_medico.php'>";
	
	/*$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje 
		from rutero_maestro r, orden_dias o where r.cod_visitador=$visitador and r.codigo_gestion=$codGestion and r.cod_ciclo=$codCiclo
		and r.dia_contacto=o.dia_contacto order by o.id";*/
	
	$sql="select r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje, 
		(select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea)linea
		from rutero_maestro_aprobado r, orden_dias o, rutero_maestro_cab_aprobado rc where r.cod_visitador=$visitador and r.cod_rutero=rc.cod_rutero 
		and rc.codigo_gestion=$codGestion
		and rc.codigo_ciclo=$codCiclo and r.dia_contacto=o.dia_contacto group by r.dia_contacto order by o.id, turno, linea";


	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th> Rutero Maestro Consolidado para registro de Asesoria
		<br>Visitador: $nombre_funcionario<br>Ciclo: $codCiclo / $nombreGestion</th></tr></table></center><br>";
	
	echo "<center><table border='1' class='textomini' cellspacing='0' width='90%'>";
	echo "<tr><th>Dia Contacto</th><th>Contactos</th><th>Planificación</th><th>Revisar Reporte</th></tr>";
	$indice=1;
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$codRutero=$dat[1];
		$codVisitador=$dat[2];
		$dia_contacto=$dat[3];
		$turno=$dat[4];
		
		$nombreLinea=$dat[6];
		$sql1="select c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, 
		c.estado, (select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea)linea, rm.turno, c.cod_med
				from rutero_maestro_detalle_aprobado c, medicos m, direcciones_medicos d, rutero_maestro_aprobado rm, rutero_maestro_cab_aprobado rc
					where c.cod_visitador=$visitador and rc.codigo_gestion='$codGestion' and rc.codigo_ciclo='$codCiclo' and 
					rm.cod_contacto=c.cod_contacto and rm.cod_rutero=rc.cod_rutero and rm.dia_contacto='$dia_contacto' and 
					(c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and c.cod_med=d.cod_med and (c.cod_zona=d.numero_direccion) 
					and rc.cod_visitador=rm.cod_visitador and rc.cod_visitador=c.cod_visitador and c.cod_visitador=rm.cod_visitador
					order by linea, rm.turno, c.orden_visita";
		//echo $sql1;
		
		$resp1=mysql_query($sql1);
		$contacto="<table class='textomini' width='100%'>";
		$contacto=$contacto."<tr><th width='5%'>Nro.</th><th width='10%'>Linea</th><th width='5%'>Orden</th><th width='25%'>Medico</th><th width='10%'>Especialidad</th><th width='10%'>Categoria</th><th width='30%'>Direccion</th><th width='5%'>Evaluar</th></tr>";
		while($dat1=mysql_fetch_array($resp1))
		{
			$orden_visita=$dat1[0];
			$pat=$dat1[1];
			$mat=$dat1[2];
			$nombre=$dat1[3];
			$direccion=$dat1[4];
			$nombre_medico="$pat $mat $nombre";
			$espe=$dat1[5];
			$cat=$dat1[6];
			$nombreLinea=$dat1[8];
			$turno=$dat1[9];
			$codMedico=$dat1[10];
			$sqlVeri="select a.cod_asesoria from asesorias a, asesorias_detalle ad where a.cod_asesoria=ad.cod_asesoria and a.cod_visitador='$visitador' and 
			a.dia_contacto='$dia_contacto' and a.cod_gestion='$codGestion' and a.cod_ciclo='$codCiclo' and ad.cod_med='$codMedico'";
			$respVeri=mysql_query($sqlVeri);
			$numFilas=mysql_num_rows($respVeri);
			if($numFilas>0){
				$codAsesoria=mysql_result($respVeri,0,0);
				
				$sqlVeri2="select * from asesorias_evaluacion where cod_asesoria=$codAsesoria and cod_med='$codMedico'";
				$respVeri2=mysql_query($sqlVeri2);
				$numFilas2=mysql_num_rows($respVeri2);
				if($numFilas2>0){
					$txtEvaluar="<img src='imagenes/si.png' title='Evaluado'>";
				}else{
					$txtEvaluar="<a href='evaluarAsesoria.php?codAsesoria=$codAsesoria&codMed=$codMedico&nombreVisitador=$nombre_funcionario&nombreLinea=$nombreLinea&diaContacto=$dia_contacto&nombreMedico=$nombre_medico'><img src='imagenes/no.png' title='Registrar Evaluación'></a>";
				}
				
			}else{
				$txtEvaluar="";
			}
			
			$contacto=$contacto."<tr><td align='center'>$indice</td><td align='center'>$nombreLinea $turno</td><td align='center'>$dat1[0]</td><td>&nbsp;$nombre_medico</td><td align='center'>$espe</td><td align='center'>$cat</td><td>$direccion </td><td>$txtEvaluar</td></tr>";
			$indice++;
		}
		$contacto=$contacto."</table>";
		echo "<tr><td align='center'>$dia_contacto</td><td align='center'>$contacto</td>
		<td><a href='registrarAsesoria.php?diaContacto=$dia_contacto&codVisitador=$visitador&codCiclo=$codCiclo&codGestion=$codGestion&nombreVisitador=$nombre_funcionario'>
		<img src='imagenes/flecha.png' title='Planificar Asesoria' width='45' heigth='45'></a></td>
		<td><a href='revisarAsesoria.php?codAsesoria=$codAsesoria&diaContacto=$dia_contacto&codVisitador=$visitador&codCiclo=$codCiclo&codGestion=$codGestion&nombreVisitador=$nombre_funcionario'>
		<img src='imagenes/documento.png' title='Ver Resultados Asesoria' width='45' heigth='45'></a></td>
		</tr>";
		
	}
	echo "</table></center><br>";
	
	echo"\n<table align='center'><tr><td><a href='javascript:history.back(-1)'><img  border='0'src='imagenes/back.png' width='40'>Volver Atras</a></td></tr></table>";
	echo "</form>";
?>