<?php
//header("Content-type: application/vnd.ms-excel"); 
//header("Content-Disposition: attachment; filename=archivo.xls"); 	
function compara_fechas($fecha1,$fecha2)
{	if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha1))
    list($dia1,$mes1,$año1)=split("/",$fecha1);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha1))
    list($dia1,$mes1,$año1)=split("-",$fecha1);
    if (preg_match("/[0-9]{1,2}\/[0-9]{1,2}\/([0-9][0-9]){1,2}/",$fecha2))
    list($dia2,$mes2,$año2)=split("/",$fecha2);
    if (preg_match("/[0-9]{1,2}-[0-9]{1,2}-([0-9][0-9]){1,2}/",$fecha2))
    list($dia2,$mes2,$año2)=split("-",$fecha2);
    $dif = mktime(0,0,0,$mes1,$dia1,$año1) - mktime(0,0,0, $mes2,$dia2,$año2);
    return ($dif);                         
}
$global_visitador=$visitador;
$rpt_territorio=$global_agencia;
require("conexion.inc");
require('estilos_reportes_regional.inc');
//esta parte saca el dia de contacto actual
	$sql_dias_ini_fin="select fecha_ini, fecha_fin from ciclos where cod_ciclo='$ciclo_rpt' and codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'";
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
//fin sacar dia contacto actual
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
if($visitador=="")
{	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and f.estado=1 and fl.codigo_linea='$global_linea' and f.cod_ciudad='$rpt_territorio' and f.cod_cargo='1011' order by f.paterno, f.materno";
	$resp_visitador=mysql_query($sql_visitador);
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Cobertura Visitadores Medicos<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='texto' width='100%' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Visitador</th><th>Total Contactos</th><th>Total Ejecutado</th><th>Cobertura</th><th>Contactos Afectados por Baja de Dias</th><th>Contactos Afectados por Baja de Medicos</th><th>Contactos Actualizados</th><th>Cobertura Actualizada</th></tr>";
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		$sql_contactos_maestro="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador' and r.codigo_linea='$global_linea' 
		and r.cod_contacto=rd.cod_contacto";
		$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
		$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
		$numero_contactos_maestro=$dat_maestro[0];

		$sql_contactos_ejecutado="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
			where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador' and r.codigo_linea='$global_linea' and r.cod_contacto=rd.cod_contacto and rd.estado='1'";
		$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
		$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
		$numero_contactos_ejecutado=$dat_ejecutado[0];		
		$cobertura_visitador=($numero_contactos_ejecutado/$numero_contactos_maestro)*100;
		$cobertura_visitador=round($cobertura_visitador);
		
		//sacamos el numero de contactos donde los medicos esten de baja
		$numero_medicos_baja=0;
		$sql_medicos_baja="select bm.cod_med, bm.inicio, bm.fin from baja_medicos bm, medicos m 
						   where bm.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio' and bm.codigo_linea='$global_linea'";
		$resp_medicos_baja=mysql_query($sql_medicos_baja);
		while($dat_medicos=mysql_fetch_array($resp_medicos_baja))
		{	$codigo_medico=$dat_medicos[0]; $inicio_baja=$dat_medicos[1]; $fin_baja=$dat_medicos[2];
			//vemos si el medico esta en nuestro rutero y si esta sacamos las fechas de contacto
			$inicio_baja_real=$inicio_baja[8].$inicio_baja[9]."/".$inicio_baja[5].$inicio_baja[6]."/".$inicio_baja[0].$inicio_baja[1].$inicio_baja[2].$inicio_baja[3];
			$fin_baja_real=$fin_baja[8].$fin_baja[9]."/".$fin_baja[5].$fin_baja[6]."/".$fin_baja[0].$fin_baja[1].$fin_baja[2].$fin_baja[3];
			$sql_verifica_medico="select r.dia_contacto from rutero r, rutero_detalle rd
			where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador'
			and r.codigo_linea='$global_linea' and rd.cod_med='$codigo_medico'";
			$resp_verifica_medico=mysql_query($sql_verifica_medico);
			while($dat_verifica=mysql_fetch_array($resp_verifica_medico))
			{	$dia_contacto_medico=$dat_verifica[0];
				for($j=1;$j<=30;$j++)
				{	if($dias[$j]==$dia_contacto_medico)
					{	$fecha_baja_contacto=$fechas[$j];
						if((compara_fechas($fecha_baja_contacto,$inicio_baja_real)>=0) and (compara_fechas($fecha_baja_contacto,$fin_baja_real)<=0))
						{	$numero_medicos_baja++;
						}
					}
					
				}
			}
			
		}
		//fin sacar contactos de medicos
		$nro_contactos_baja_total=0;
		$sql_baja_dias="select distinct(b.dia_contacto), b.turno from baja_dias b, baja_dias_detalle bd, baja_dias_detalle_visitador bdv
		where b.codigo_baja=bd.codigo_baja and bd.codigo_baja=bdv.codigo_baja and b.ciclo='$ciclo_rpt' and b.gestion='$gestion_rpt' and bd.codigo_linea='$global_linea'
		and  bdv.codigo_visitador='$codigo_visitador'";
		$resp_baja_dias=mysql_query($sql_baja_dias);
		while($dat_baja_dias=mysql_fetch_array($resp_baja_dias))
		{	$dia_contacto_baja=$dat_baja_dias[0];
			$turno_contacto_baja=$dat_baja_dias[1];
			$sql_nro_contactos_baja="select count(r.cod_contacto) from rutero r, rutero_detalle rd
			where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$codigo_visitador'
			and r.dia_contacto='$dia_contacto_baja' and turno='$turno_contacto_baja' and r.codigo_linea='$global_linea'";
			$resp_nro_contactos_baja=mysql_query($sql_nro_contactos_baja);
			$dat_contactos_baja=mysql_fetch_array($resp_nro_contactos_baja);
			$nro_contactos_baja_dias=$dat_contactos_baja[0];
			$nro_contactos_baja_total=$nro_contactos_baja_total+$nro_contactos_baja_dias;
		}
		$contactos_reales=$numero_contactos_maestro-$nro_contactos_baja_total-$numero_medicos_baja;
		$cobertura_real=($numero_contactos_ejecutado/$contactos_reales)*100;
		$cobertura_real=round($cobertura_real);
		echo "<tr><td align='center'>$indice_tabla</td><td>$nombre_visitador</td><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td><td align='center'>$cobertura_visitador %</td><td align='center'>$nro_contactos_baja_total</td><td align='center'>$numero_medicos_baja</td><td align='center'>$contactos_reales</td><td align='center'>$cobertura_real %</td></tr>";
		$indice_tabla++;
	}	
	echo "</table></center><br>";
}
else
{	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Cobertura Visitadores Medicos<br>Visitador $nombre_visitador Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='80%' cellspacing='0'>";
	echo "<tr><th>Total Contactos</th><th>Total Ejecutado</th><th>Cobertura</th><th>Contactos Afectados por Baja de Dias</th><th>Contactos Afectados por Baja de Medicos</th><th>Contactos Actualizados</th><th>Cobertura Actualizada</th></tr>";
	$sql_contactos_maestro="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador' and r.codigo_linea='$global_linea' and r.cod_contacto=rd.cod_contacto";
	$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
	$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
	$numero_contactos_maestro=$dat_maestro[0];
	$sql_contactos_ejecutado="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador' and r.codigo_linea='$global_linea' and r.cod_contacto=rd.cod_contacto and rd.estado='1'";
	$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
	$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
	$numero_contactos_ejecutado=$dat_ejecutado[0];		
	$cobertura_visitador=($numero_contactos_ejecutado/$numero_contactos_maestro)*100;
	$cobertura_visitador=round($cobertura_visitador);
	//sacamos el numero de contactos donde los medicos esten de baja
	$numero_medicos_baja=0;
	$sql_medicos_baja="select bm.cod_med, bm.inicio, bm.fin from baja_medicos bm, medicos m 
						where bm.cod_med=m.cod_med and m.cod_ciudad='$rpt_territorio'
						and bm.codigo_linea='$global_linea'";
	$resp_medicos_baja=mysql_query($sql_medicos_baja);
	while($dat_medicos=mysql_fetch_array($resp_medicos_baja))
	{	$codigo_medico=$dat_medicos[0]; $inicio_baja=$dat_medicos[1]; $fin_baja=$dat_medicos[2];
		//vemos si el medico esta en nuestro rutero y si esta sacamos las fechas de contacto
		$inicio_baja_real=$inicio_baja[8].$inicio_baja[9]."/".$inicio_baja[5].$inicio_baja[6]."/".$inicio_baja[0].$inicio_baja[1].$inicio_baja[2].$inicio_baja[3];
		$fin_baja_real=$fin_baja[8].$fin_baja[9]."/".$fin_baja[5].$fin_baja[6]."/".$fin_baja[0].$fin_baja[1].$fin_baja[2].$fin_baja[3];
		$sql_verifica_medico="select r.dia_contacto from rutero r, rutero_detalle rd
		where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador'
		and r.codigo_linea='$global_linea' and rd.cod_med='$codigo_medico'";
		$resp_verifica_medico=mysql_query($sql_verifica_medico);
		while($dat_verifica=mysql_fetch_array($resp_verifica_medico))
		{	$dia_contacto_medico=$dat_verifica[0];
			for($j=1;$j<=30;$j++)
			{	if($dias[$j]==$dia_contacto_medico)
				{	$fecha_baja_contacto=$fechas[$j];
					if((compara_fechas($fecha_baja_contacto,$inicio_baja_real)>=0) and (compara_fechas($fecha_baja_contacto,$fin_baja_real)<=0))
					{	$numero_medicos_baja++;
					}
				}
				
			}
		}
		
	}
	//fin sacar contactos de medicos
	//sacamos los contactos que estan afectados por los dias de baja
	$nro_contactos_baja_total=0;
	$sql_baja_dias="select b.dia_contacto, b.turno from baja_dias b, baja_dias_detalle bd, baja_dias_detalle_visitador bdv
where b.codigo_baja=bd.codigo_baja and bd.codigo_baja=bdv.codigo_baja and b.ciclo='$ciclo_rpt' and b.gestion='$gestion_rpt' and bd.codigo_linea='$global_linea'
and  bdv.codigo_visitador='$global_visitador'";
	$resp_baja_dias=mysql_query($sql_baja_dias);
	while($dat_baja_dias=mysql_fetch_array($resp_baja_dias))
	{	$dia_contacto_baja=$dat_baja_dias[0];
		$turno_contacto_baja=$dat_baja_dias[1];
		$sql_nro_contactos_baja="select count(r.cod_contacto) from rutero r, rutero_detalle rd
		where r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_visitador='$global_visitador'
		and r.dia_contacto='$dia_contacto_baja' and turno='$turno_contacto_baja' and r.codigo_linea='$global_linea'";
		$resp_nro_contactos_baja=mysql_query($sql_nro_contactos_baja);
		$dat_contactos_baja=mysql_fetch_array($resp_nro_contactos_baja);
		$nro_contactos_baja_dias=$dat_contactos_baja[0];
		$nro_contactos_baja_total=$nro_contactos_baja_total+$nro_contactos_baja_dias;
	}
	$contactos_reales=$numero_contactos_maestro-$nro_contactos_baja_total-$numero_medicos_baja;
	$cobertura_real=($numero_contactos_ejecutado/$contactos_reales)*100;
	$cobertura_real=round($cobertura_real);
	//fin sacar contactos dias baja
	echo "<tr><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td><td align='center'>$cobertura_visitador %</td><td align='center'>$nro_contactos_baja_total</td><td align='center'>$numero_medicos_baja</td><td align='center'>$contactos_reales</td><td align='center'>$cobertura_real %</td></tr>";

}
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>