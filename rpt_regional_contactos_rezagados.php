<?php
$global_visitador=$visitador;
$global_gestion=$gestion_rpt;
$ciclo_global=$ciclo_rpt;
require("conexion.inc");
require("estilos_reportes_regional.inc");	
	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	
//esta parte saca el dia de contacto actual
	$sql_dias_ini_fin="select fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_global' and codigo_linea='$global_linea'";
	$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
	$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
	$fecha_ini_actual=$dat_dias[0];
	$fecha_fin_actual=$dat_dias[1];
	$fecha_actual=$fecha_ini_actual;
	$inicio=$fecha_ini_actual;
	$k=0;
	list($anio,$mes,$dia)=explode("-",$fecha_actual);
	$dia1=$dia;
		while($inicio<$fecha_fin_actual)
		{
			//echo $inicio."<br>";
			$ban=0;
			while($ban==0)
			{	$nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
				$dia_semana=date("l",$nueva1);
				if($dia_semana=='Sunday' or $dia_semana=='Saturday')
				{	$dia1=$dia1+1;
				}
				else
				{	$ban=1;
				}
			}
			$num_dia=intval($k/5)+1;
			if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
			if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
			if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
			if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
			if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}
			
			$fecha_actual=date("Y-m-d",$nueva1);
			$inicio=$fecha_actual;
			list($anio,$mes,$dia)=explode("-",$fecha_actual);
			$dia1=$dia+1;			
			$fecha_actual_formato="$dia/$mes/$anio";
			$fechas[$k]=$fecha_actual_formato;
			$k++;
		}
	//fin vectores dias y fechas
	$contador=1;
	//desde aqui sacamos las fechas nuevas
	$fecha_sistema=date("d/m/Y");
	list($d_actual,$m_actual,$a_actual)=explode("/",$fecha_sistema);
	$sec_actual=mktime(0,0,0,$m_actual,$d_actual,$a_actual);
	for($i=0;$i<=$k-1;$i++)
	{	list($d_comp,$m_comp,$a_comp)=explode("/",$fechas[$i]);	
		$sec_comp=mktime(0,0,0,$m_comp,$d_comp,$a_comp);
		if($sec_comp<=$sec_actual)
		{	$posicion=$i;
		}
	}
	$dia_contacto_sistema=$dias[$posicion];
$sql_id="select id from orden_dias where dia_contacto='$dia_contacto_sistema'";
$resp_id=mysql_query($sql_id);
$dat_id=mysql_fetch_array($resp_id);
$id_sistema=$dat_id[0];
if($id_sistema=="")
{	$id_sistema=21;
}
//fin sacra dia contacto actual
$bandera=0;
//esta primera condicion saca el reporte por medico resumido
if($semana=="" && $dia_contacto=="" && $formato==0)
{	$sql_medicos_asignados="select ma.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medico_asignado_visitador ma, medicos m
							where ma.codigo_visitador='$global_visitador' and ma.codigo_linea='$global_linea' and m.cod_med=ma.cod_med order by m.ap_pat_med, m.ap_mat_med";
	$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Rezagados<br>Formato Resumido</th></tr></table></center><br>";
	echo "<table border=1 width='80%' class='texto' align='center' cellspacing=0><tr><th>Medico</th><th>Especialidad</th><th>Contactos Rutero Maestro</th><th>Contactos Rezagados</th></tr>";
	while($dat_medicos_asignados=mysql_fetch_array($resp_medicos_asignados))
	{	$codigo_medico=$dat_medicos_asignados[0];
		$nombre_medico="$dat_medicos_asignados[1] $dat_medicos_asignados[2] $dat_medicos_asignados[3]";
		$sql_especialidad="select cod_especialidad, categoria_med from categorias_lineas 
						where cod_med='$codigo_medico' and codigo_linea='$global_linea'";
		$resp_especialidad=mysql_query($sql_especialidad);
		$txt_espe="<table border='0' class='textomini' width='100%'>";
		while($dat_especialidad=mysql_fetch_array($resp_especialidad))
		{	$cod_espe=$dat_especialidad[0];
			$cat_med=$dat_especialidad[1];
			$txt_espe="$txt_espe<tr><td width='80%'>$cod_espe</td><td width='20%'>$cat_med</td></tr>";
		}
		$txt_espe="$txt_espe</table>";		
		$sql_contactos_maestro="select count(rd.cod_med) from rutero r, rutero_detalle rd
		where r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and r.codigo_linea='$global_linea' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto and rd.cod_med='$codigo_medico'";
		$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
		$dat_contactos_maestro=mysql_fetch_array($resp_contactos_maestro);
		$numero_contactos_maestro=$dat_contactos_maestro[0];
		
		$sql_contactos_rezagados="select r.dia_contacto, r.turno, rd.cod_contacto from rutero r, rutero_detalle rd, orden_dias o
		WHERE r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and r.cod_visitador='$global_visitador'
		and rd.estado='0' and r.codigo_linea='$global_linea' and rd.cod_med='$codigo_medico' and r.cod_contacto=rd.cod_contacto and r.dia_contacto=o.dia_contacto
		and o.id<'$id_sistema'";
		$resp_contactos_rezagados=mysql_query($sql_contactos_rezagados);
		$numero_contactos_rezagados=0;
		//realiza la comprobacion de que el medico no este en un dia de baja
		while($dat_contactos_rezagados=mysql_fetch_array($resp_contactos_rezagados))
		{	$dia_rezagado=$dat_contactos_rezagados[0];	
			$turno_rezagado=$dat_contactos_rezagados[1];
			$contacto_rezagado=$dat_contactos_rezagados[2];
			$sql_rezagados="select b.codigo_baja from baja_dias b, baja_dias_detalle bd 
			where ciclo='$ciclo_global' and gestion='$codigo_gestion' and codigo_ciudad='$global_agencia' and
			dia_contacto='$dia_rezagado' and turno='$turno_rezagado' and b.codigo_baja=bd.codigo_baja and bd.codigo_linea='$global_linea'";
			$resp_rezadados=mysql_query($sql_rezagados);
			$filas_rezagado=mysql_num_rows($resp_rezadados);
			for($l=1;$l<=20;$l++)
			{	if($dias[$l]==$dia_rezagado)
				{	$fecha_rezagada=$fechas[$l];
				}
			}
			$fecha_rezagada_real=$fecha_rezagada[6].$fecha_rezagada[7].$fecha_rezagada[8].$fecha_rezagada[9]."-".$fecha_rezagada[3].$fecha_rezagada[4]."-".$fecha_rezagada[0].$fecha_rezagada[1];
			$sql_baja_medico=mysql_query("select cod_med from baja_medicos where cod_med='$codigo_medico' and inicio<='$fecha_rezagada_real' and fin>='$fecha_rezagada_real'");
			$filas_baja_medico=mysql_num_rows($sql_baja_medico);
			if($filas_rezagado==0 and $filas_baja_medico==0)
			{	$numero_contactos_rezagados++;
			}
			/*if($filas_rezagado==0)
			{	$numero_contactos_rezagados++;
			}
			if($filas_baja_medico!=0)
			{	echo "aqui esta de baja el medico $nombre_medico";
			}*/
		}
		if($numero_contactos_rezagados!=0)
		{	echo "<tr bgcolor=$fondo_fila><td>$nombre_medico</td><td>$txt_espe</td><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_rezagados</td></tr>";
		}		
	}
	///veamos que pasa
	$bandera=1;
}
if($semana=="" && $dia_contacto=="" && $formato==1)
{	$sql="select r.cod_contacto, r.dia_contacto, r.turno from rutero r, orden_dias o where r.dia_contacto=o.dia_contacto and o.id<$id_sistema and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Rezagados</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dat[2];
		$sql_baja_dias="select bd.codigo_linea from baja_dias b, baja_dias_detalle bd
		where b.codigo_baja=bd.codigo_baja and bd.codigo_linea='$global_linea' and b.ciclo='$ciclo_global' and b.gestion='$codigo_gestion'
		and	b.dia_contacto='$dia_contacto' and b.turno='$turno'";
		$resp_baja_dias=mysql_query($sql_baja_dias);
		$filas_baja_dias=mysql_num_rows($resp_baja_dias);
		if($filas_baja_dias==0)
		{	$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='0' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$filas_detalle=mysql_num_rows($resp_detalle);
			if($filas_detalle!=0)
			{	$medicos_visitados="";
				$medicos_visitados.="<table border=1 width='100%' class='texto'><tr><th>Orden Visita</th><th>Medico</th><th>Especialidad</th><th>Categoria</th></tr>";
				while($dat_detalle=mysql_fetch_array($resp_detalle))
				{	$orden_visita=$dat_detalle[0];
					$cod_med=$dat_detalle[1];
					$cod_especialidad=$dat_detalle[2];
					$categoria=$dat_detalle[3];
					$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
					$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
					$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
					for($l=1;$l<=20;$l++)
					{	if($dias[$l]==$dia_contacto)
						{	$fecha_rezagada=$fechas[$l];
						}
					}
					$fecha_rezagada_real=$fecha_rezagada[6].$fecha_rezagada[7].$fecha_rezagada[8].$fecha_rezagada[9]."-".$fecha_rezagada[3].$fecha_rezagada[4]."-".$fecha_rezagada[0].$fecha_rezagada[1];
					$sql_baja_medico=mysql_query("select cod_med from baja_medicos where cod_med='$cod_med' and inicio<='$fecha_rezagada_real' and fin>='$fecha_rezagada_real'");
					$filas_baja_medico=mysql_num_rows($sql_baja_medico);
					if($filas_baja_medico==0)
					{	$medicos_visitados.="<tr><td align='center' width='15%'>$orden_visita</td><td width='55%'>$nombre_medico</td><td align='center' width='15%'>$cod_especialidad</td><td align='center' width='15%'>$categoria</td></tr>";
					}					
				}
				$medicos_visitados.="</table>";
				echo "<tr><td align='left'>$dia_contacto $turno</td><td align='center'>$medicos_visitados</td></tr>";		
			}
		}
		
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($semana!="" and $bandera==0)
{	$consulta_semana="(r.dia_contacto='Lunes $semana' or r.dia_contacto='Martes $semana' or r.dia_contacto='Miercoles $semana' or r.dia_contacto='Jueves $semana' or r.dia_contacto='Viernes $semana')";
	$sql="select r.cod_contacto, r.dia_contacto, r.turno from rutero r, orden_dias o where $consulta_semana and r.dia_contacto=o.dia_contacto and o.id<$id_sistema and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Rezagados<br>Semana $semana</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Dia Contacto</th><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dat[2];
		$sql_baja_dias="select bd.codigo_linea from baja_dias b, baja_dias_detalle bd
		where b.codigo_baja=bd.codigo_baja and bd.codigo_linea='$global_linea' and b.ciclo='$ciclo_global' and b.gestion='$codigo_gestion'
		and	b.dia_contacto='$dia_contacto' and b.turno='$turno'";
		$resp_baja_dias=mysql_query($sql_baja_dias);
		$filas_baja_dias=mysql_num_rows($resp_baja_dias);
		if($filas_baja_dias==0)
		{
			$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='0' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$filas_detalle=mysql_num_rows($resp_detalle);
			if($filas_detalle!=0)
			{	$medicos_visitados="";
				$medicos_visitados.="<table border=0 width='100%' class='texto'><tr><th>Orden Visita</th><th>Medico</th><th>Especialidad</th><th>Categoria</th></tr>";
				while($dat_detalle=mysql_fetch_array($resp_detalle))
				{	$orden_visita=$dat_detalle[0];
					$cod_med=$dat_detalle[1];
					$cod_especialidad=$dat_detalle[2];
					$categoria=$dat_detalle[3];
					$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
					$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
					$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
					for($l=1;$l<=20;$l++)
					{	if($dias[$l]==$dia_contacto)
						{	$fecha_rezagada=$fechas[$l];
						}
					}
					$fecha_rezagada_real=$fecha_rezagada[6].$fecha_rezagada[7].$fecha_rezagada[8].$fecha_rezagada[9]."-".$fecha_rezagada[3].$fecha_rezagada[4]."-".$fecha_rezagada[0].$fecha_rezagada[1];
					$sql_baja_medico=mysql_query("select cod_med from baja_medicos where cod_med='$cod_med' and inicio<='$fecha_rezagada_real' and fin>='$fecha_rezagada_real'");
					$filas_baja_medico=mysql_num_rows($sql_baja_medico);
					if($filas_baja_medico==0)
					{	$medicos_visitados.="<tr><td align='center' width='15%'>$orden_visita</td><td width='55%'>$nombre_medico</td><td align='center' width='15%'>$cod_especialidad</td><td align='center' width='15%'>$categoria</td></tr>";
					}
				}
				$medicos_visitados.="</table>";
				echo "<tr><td align='center'>$dia_contacto $turno</td><td align='center' class='texto'>$medicos_visitados</td></tr>";		
			}
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($dia_contacto!="" and $bandera==0)
{	$sql="select r.cod_contacto, r.dia_contacto, r.turno from rutero r, orden_dias o where r.dia_contacto='$dia_contacto' and o.id<$id_sistema and o.id<$id_sistema and r.dia_contacto=o.dia_contacto and r.cod_ciclo='$ciclo_global' and r.cod_visitador='$global_visitador' order by o.id";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Contactos Rezagados<br>Dia de Contacto $dia_contacto $turno</th></tr></table></center><br>";
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Medicos</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod_contacto=$dat[0];
		$dia_contacto=$dat[1];
		$turno=$dia_contacto[2];
		$sql_baja_dias="select bd.codigo_linea from baja_dias b, baja_dias_detalle bd
		where b.codigo_baja=bd.codigo_baja and bd.codigo_linea='$global_linea' and b.ciclo='$ciclo_global' and b.gestion='$codigo_gestion'
		and	b.dia_contacto='$dia_contacto' and b.turno='$turno'";
		$resp_baja_dias=mysql_query($sql_baja_dias);
		$filas_baja_dias=mysql_num_rows($resp_baja_dias);
		if($filas_baja_dias==0)
		{
			$sql_detalle="select orden_visita, cod_med, cod_especialidad, categoria_med from rutero_detalle where cod_contacto='$cod_contacto' and estado='0' order by orden_visita";
			$resp_detalle=mysql_query($sql_detalle);
			$filas_detalle=mysql_num_rows($resp_detalle);
			if($filas_detalle!=0)
			{	$medicos_visitados="";
				$medicos_visitados.="<table border=0 width='100%' class='texto'><tr><th>Orden Visita</th><th>Medico</th><th>Especialidad</th><th>Categoria</th></tr>";
				while($dat_detalle=mysql_fetch_array($resp_detalle))
				{	$orden_visita=$dat_detalle[0];
					$cod_med=$dat_detalle[1];
					$cod_especialidad=$dat_detalle[2];
					$categoria=$dat_detalle[3];
					$sql_nombre_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_med'");
					$dat_nombre_med=mysql_fetch_array($sql_nombre_med);
					$nombre_medico="$dat_nombre_med[0] $dat_nombre_med[1] $dat_nombre_med[2]";
					for($l=1;$l<=20;$l++)
					{	if($dias[$l]==$dia_contacto)
						{	$fecha_rezagada=$fechas[$l];
						}
					}
					$fecha_rezagada_real=$fecha_rezagada[6].$fecha_rezagada[7].$fecha_rezagada[8].$fecha_rezagada[9]."-".$fecha_rezagada[3].$fecha_rezagada[4]."-".$fecha_rezagada[0].$fecha_rezagada[1];
					$sql_baja_medico=mysql_query("select cod_med from baja_medicos where cod_med='$cod_med' and inicio<='$fecha_rezagada_real' and fin>='$fecha_rezagada_real'");
					$filas_baja_medico=mysql_num_rows($sql_baja_medico);
					if($filas_baja_medico==0)
					{	$medicos_visitados.="<tr><td align='center' width='15%'>$orden_visita</td><td width='55%'>$nombre_medico</td><td align='center' width='15%'>$cod_especialidad</td><td align='center' width='15%'>$categoria</td></tr>";
					}
				}
				$medicos_visitados.="</table>";
				echo "<tr><td align='center' class='texto'>$medicos_visitados</td></tr>";		
			}
		}
	}
	echo "</table></center><br>";
	$bandera=1;
}
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>