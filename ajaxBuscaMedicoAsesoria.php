<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");
	require("funcion_nombres.php");

	
	$nombreMedico=$_GET['nombreMedico'];
	$codCiclo=$_GET['codCiclo'];
	$codGestion=$_GET['codGestion'];
	
	echo "<center><table border='1' class='texto' cellspacing='0' width='90%'>";
	
	echo "<tr><th>Medico</th><th>Especialidad</th><th>CUP</th><th>Linea</th><th>Visitador / Contactos</th></tr>";	
		
	$sql1="select rd.cod_med, concat(m.ap_pat_med,' ', m.ap_mat_med,' ',m.nom_med)medicos, 
		rd.cod_especialidad, rd.categoria_med, m.cod_closeup, m.cod_catcloseup, 
		(select l.nombre_linea from lineas l where l.codigo_linea=rc.codigo_linea)linea, 
		rc.cod_visitador, (select concat(f.paterno,' ',f.nombres) from funcionarios f where f.codigo_funcionario=rc.cod_visitador)visitador
		from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, 
		rutero_maestro_detalle_aprobado rd, medicos m where rc.cod_rutero=rm.cod_rutero and 
		rm.cod_contacto=rd.cod_contacto and rd.cod_med=m.cod_med and rc.codigo_gestion=$codGestion and rc.codigo_ciclo=$codCiclo 
		and rc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad=$global_agencia) and
		(m.ap_pat_med like '%$nombreMedico%' or m.ap_mat_med like '%$nombreMedico%' or m.nom_med like '%$nombreMedico%') 
		and rc.cod_visitador=rm.cod_visitador and rc.cod_visitador=rd.cod_visitador and rc.cod_visitador=rm.cod_visitador
		group by rd.cod_med, linea;";
	$resp1=mysql_query($sql1);
	$indice=0;
	while($dat1=mysql_fetch_array($resp1))
	{
		$codMed=$dat1[0];
		$nombreMedico=$dat1[1];
		$codEspecialidad=$dat1[2]." ".$dat1[3];
		$codCUP=$dat1[4]." ".$dat1[5];
		$nombreLinea=$dat1[6];
		$codVisitador=$dat1[7];
		$nombreVisitador=$dat1[8];
		
		$sqlDias="select rm.dia_contacto from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, orden_dias o
			where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rm.dia_contacto=o.dia_contacto and rc.codigo_gestion=$codGestion 
			and rc.codigo_ciclo=$codCiclo and 
			rd.cod_med=$codMed and rc.cod_visitador=$codVisitador order by o.id;";
		$respDias=mysql_query($sqlDias);
		$cadenaDias="";
		while($datDias=mysql_fetch_array($respDias)){
			$cadenaDias=$cadenaDias." "."<a href='registrarAsesoria.php?diaContacto=$datDias[0]&codGestion=$codGestion&codCiclo=$codCiclo&codVisitador=$codVisitador&nombreVisitador=$nombreVisitador&codMedX=$codMed'>$datDias[0]</a>";
		}
		
		$indice++;
		echo "<tr>
		<td align='center'>$nombreMedico</td><td>$codEspecialidad</td>
		<td align='center'>$codCUP</td>
		<td align='left'>$nombreLinea</td>
		<td align='left'>$nombreVisitador ($cadenaDias)</td>
		</tr>";

	}
	
	echo "</table></center><br>";

	echo "</form>";
?>