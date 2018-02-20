<?php
$global_visitador=$visitador;
$global_linea=$linea_rpt;
require("conexion.inc");
require("estilos_reportes_central.inc");
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
//creamos parte de la consulta para que filtre los dias de contacto(dia, semana, todo el ciclo)
$sql_dias_contacto="select dia_contacto from orden_dias where id>=$rpt_dia_inicio and id<=$rpt_dia_final";
$resp_dias_contacto=mysql_query($sql_dias_contacto);
$num_filas=mysql_num_rows($resp_dias_contacto);
$i=1;
$sql_dias="(";
while($datos_dias_contacto=mysql_fetch_array($resp_dias_contacto))
{	$dia_contacto=$datos_dias_contacto[0];
	$sql_dias="$sql_dias r.dia_contacto='$dia_contacto'";
	if($i==$num_filas)
	{	$sql_dias="$sql_dias";
	}
	else
	{	$sql_dias="$sql_dias or";
	}
	$i++;
}
$sql_dias="$sql_dias)";
//fin consulta dias
if($parametro==0)
{	$sql_contactos_maestro="select count(rd.cod_contacto) from rutero_detalle_utilizado rd, rutero_utilizado r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and $sql_dias and r.codigo_linea='$linea_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto";
	$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
	$dat_maestro=mysql_fetch_array($resp_contactos_maestro);
	$numero_contactos_maestro=$dat_maestro[0];
	
	$sql_contactos_ejecutado="select count(rd.cod_contacto) from rutero_detalle rd, rutero r 
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and $sql_dias and r.codigo_linea='$linea_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto and rd.estado='1'";
	$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
	$dat_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
	$numero_contactos_ejecutado=$dat_ejecutado[0];
	
	$sql_medicos_maestro="select count(DISTINCT (rd.cod_med)) from rutero_detalle_utilizado rd, rutero_utilizado r 	
					where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and $sql_dias and r.codigo_linea='$linea_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto";
	$resp_medicos_maestro=mysql_query($sql_medicos_maestro);
	$dat_medicos_maestro=mysql_fetch_array($resp_medicos_maestro);
	$medicos_maestro=$dat_medicos_maestro[0];
	
	$sql_medicos_ejecutado="select count(DISTINCT (rd.cod_med)) from rutero_detalle rd, rutero r 	
					where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and $sql_dias and r.codigo_linea='$linea_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto";
	$resp_medicos_ejecutado=mysql_query($sql_medicos_ejecutado);
	$dat_medicos_ejecutado=mysql_fetch_array($resp_medicos_ejecutado);
	$medicos_ejecutado=$dat_medicos_ejecutado[0];
	
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Rutero Maestro vs. Rutero Ejecutado<br>Formato Resumido<br>Visitador $nombre_visitador</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='80%' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Rutero Maestro</th><th>Rutero Ejecutado</th></tr>";
	echo "<tr><th>Número de Contactos</th><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td></tr>";
	echo "<tr><th>Número de Medicos en el Rutero</th><td align='center'>$medicos_maestro</td><td align='center'>$medicos_ejecutado</td></tr>";
	echo "</table></center><br>";
}
if($parametro==1)
{	$sql_medicos_asignados="select ma.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medico_asignado_visitador ma, medicos m
							where ma.codigo_visitador='$global_visitador' and ma.codigo_linea='$global_linea' and m.cod_med=ma.cod_med order by m.ap_pat_med, m.ap_mat_med";
	$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Rutero Maestro vs. Rutero Ejecutado<br>Formato Detallado<br>Visitador $nombre_visitador</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><th>Leyenda:</th><th>Medicos con diferencia del numero de contactos en rutero maestro y ejecutado</th><td bgcolor='#BBBBBB' width='30%'></td></tr></table></center><br>";
	$indice_tabla=1;
	echo "<table border=1 width='80%' class='texto' align='center' cellspacing=0><tr><th>&nbsp;</th><th>Medico</th><th>Especialidad</th><th>Contactos Rutero Maestro</th><th>Contactos Rutero Ejecutado</th><th>Diferencia</th></tr>";
	while($dat_medicos_asignados=mysql_fetch_array($resp_medicos_asignados))
	{	$codigo_medico=$dat_medicos_asignados[0];
		$nombre_medico="$dat_medicos_asignados[1] $dat_medicos_asignados[2] $dat_medicos_asignados[3]";
		$sql_especialidad="select cod_especialidad, categoria_med from categorias_lineas 
						where cod_med='$codigo_medico' and codigo_linea='$linea_rpt'";
		$resp_especialidad=mysql_query($sql_especialidad);
		$txt_espe="<table border='0' class='textomini' width='100%'>";
		while($dat_especialidad=mysql_fetch_array($resp_especialidad))
		{	$cod_espe=$dat_especialidad[0];
			$cat_med=$dat_especialidad[1];
			$txt_espe="$txt_espe<tr><td width='80%'>$cod_espe</td><td width='20%'>$cat_med</td></tr>";
		}
		$txt_espe="$txt_espe</table>";
		$sql_contactos_maestro="select count(rd.cod_med) from rutero_utilizado r, rutero_detalle_utilizado rd
		where r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt' and $sql_dias and r.codigo_linea='$linea_rpt' and r.cod_visitador='$global_visitador' and r.cod_contacto=rd.cod_contacto and rd.cod_med='$codigo_medico'";
		$resp_contactos_maestro=mysql_query($sql_contactos_maestro);
		$dat_contactos_maestro=mysql_fetch_array($resp_contactos_maestro);
		$numero_contactos_maestro=$dat_contactos_maestro[0];
		
		$sql_contactos_ejecutado="select count(rd.cod_med) from rutero r, rutero_detalle rd
		where r.cod_ciclo='$ciclo_rpt' and r.cod_visitador='$global_visitador' and $sql_dias and r.codigo_linea='$linea_rpt' and r.codigo_gestion='$gestion_rpt' and r.cod_contacto=rd.cod_contacto and rd.cod_med='$codigo_medico' and rd.estado='1'";
		$resp_contactos_ejecutado=mysql_query($sql_contactos_ejecutado);
		$dat_contactos_ejecutado=mysql_fetch_array($resp_contactos_ejecutado);
		$numero_contactos_ejecutado=$dat_contactos_ejecutado[0];
		$diferencia=$numero_contactos_ejecutado-$numero_contactos_maestro;
		if(($numero_contactos_ejecutado==0 and $numero_contactos_maestro==0))
		{	
		}
		else
		{	if($numero_contactos_maestro!=$numero_contactos_ejecutado)
			{	$fondo_fila="#BBBBBB";
			}
			else
			{	$fondo_fila="";
			}
			echo "<tr bgcolor=$fondo_fila><td align='center'>$indice_tabla</td><td>$nombre_medico</td><td>$txt_espe</td><td align='center'>$numero_contactos_maestro</td><td align='center'>$numero_contactos_ejecutado</td><td align='center'>$diferencia</td></tr>";
			$indice_tabla++;
		}
	}
	echo "</table>";
}
echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>