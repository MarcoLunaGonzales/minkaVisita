<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require('estilos_reportes_administracion.inc');
$sql_territorio="select descripcion from ciudades where cod_ciudad='$rpt_territorio'";
$resp_territorio=mysql_query($sql_territorio);
$dat_territorio=mysql_fetch_array($resp_territorio);
$nombre_territorio=$dat_territorio[0];
echo "<table align='center' class='textotit'><tr><th>Reporte Observaciones en Boletas de Visita</th></tr></table><br>";
$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, l.codigo_linea, l.nombre_linea
				from funcionarios f, funcionarios_lineas fl, lineas l
				where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea=l.codigo_linea
				and f.cod_ciudad='$rpt_territorio' and f.cod_cargo='1011' and f.estado=1 order by nombre_linea";
$resp_visitadores=mysql_query($sql_visitadores);
while($dat_visitadores=mysql_fetch_array($resp_visitadores))
{	$visitador=$dat_visitadores[0];
	$nombre_visitador="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
	$global_linea=$dat_visitadores[4];
	$nombre_linea=$dat_visitadores[5];
	
	$sql_rutero="select cod_rutero from rutero_maestro_cab
			where cod_visitador='$visitador' and estado_aprobado='1' and codigo_linea='$global_linea'";
	$resp_rutero=mysql_query($sql_rutero);
	$dat_rutero=mysql_fetch_array($resp_rutero);
	$codigo_rutero_maestro=$dat_rutero[0];

	$sql_med_vis="select m.cod_med, c.cod_especialidad, c.categoria_med
				from medico_asignado_visitador m, categorias_lineas c
				where m.codigo_visitador='$visitador' AND m.cod_med=c.cod_med and c.codigo_linea='$global_linea'";
	$resp_med_vis=mysql_query($sql_med_vis);
	echo "<table border=1 class='texto' width='100%' align='center'>";
	echo "<tr><th colspan='6'>$nombre_visitador $nombre_linea</th></tr>";
	echo "<tr><th>Medico</th><th>Espe.</th><th>Cat.</th><th>Contactos<br>Grilla</th><th>Contactos<br>Rutero Maestro</th><th>Dias de Contacto</th></tr>";
	while($datos_med_vis=mysql_fetch_array($resp_med_vis))
	{	$cod_med=$datos_med_vis[0];
		$espe_med=$datos_med_vis[1];
		$cat_med=$datos_med_vis[2];
		$sql_cuenta_med="select count(rd.cod_med) 
				from rutero_maestro_detalle rd, rutero_maestro r
				WHERE r.cod_rutero='$codigo_rutero_maestro' AND r.cod_visitador='$visitador' AND r.cod_contacto=rd.cod_contacto and rd.cod_med='$cod_med' and rd.cod_especialidad='$espe_med' and rd.categoria_med='$cat_med'";
		//echo $sql_cuenta_med."<br>";
		$resp_cuenta_med=mysql_query($sql_cuenta_med);
		$datos_cuenta_med=mysql_fetch_array($resp_cuenta_med);
		$num_veces_medico=$datos_cuenta_med[0];
		/*$sql_grilla="select gd.cod_especialidad, gd.cod_categoria, gd.frecuencia 
				 from grilla g, grilla_detalle gd 
				 where g.codigo_grilla=gd.codigo_grilla and gd.cod_especialidad='$espe_med' and cod_categoria='$cat_med' and 
				 codigo_linea='$global_linea' and g.agencia='$rpt_territorio' and g.estado='1'";*/
		$sql_grilla="select gd.cod_especialidad, gd.cod_categoria, gd.frecuencia 
				 from grilla g, grilla_detalle gd 
				 where g.codigo_grilla=gd.codigo_grilla and gd.cod_especialidad='$espe_med' and cod_categoria='$cat_med' and 
				 codigo_linea='$global_linea' and g.agencia='102' and g.estado='1'";
		$resp_grilla=mysql_query($sql_grilla);
		$dat_grilla=mysql_fetch_array($resp_grilla);
		$frec_grilla=$dat_grilla[2];
		
		if($num_veces_medico>$frec_grilla)
		{	$bandera=1;
			$cod_medico_inf=$cod_med;
			$sql_nom_med=mysql_query("select ap_pat_med, ap_mat_med, nom_med from medicos where cod_med='$cod_medico_inf'");
			$dat_nom_med=mysql_fetch_array($sql_nom_med);
			$nombre_medico="$dat_nom_med[0] $dat_nom_med[1] $dat_nom_med[2]";	
			$sql_dias_contacto="select r.dia_contacto, r.turno
								from rutero_maestro r, rutero_maestro_detalle rd
								where r.cod_contacto=rd.cod_contacto and r.cod_rutero='$codigo_rutero_maestro' 
								and rd.cod_med='$cod_medico_inf'";
			$resp_dias_contacto=mysql_query($sql_dias_contacto);
			$cadena_dias="";
			while($dat_dias_contacto=mysql_fetch_array($resp_dias_contacto))
			{	$dia_contacto=$dat_dias_contacto[0];
				$turno_contacto=$dat_dias_contacto[1];
				$cadena_dias="$cadena_dias $dia_contacto $turno_contacto - ";
			}
			//echo "Medico: $nombre_medico Contactos en Rutero: $num_veces_medico Frec x Grilla: $frec_grilla Espe: $espe_med Cat: $cat_med<br>";
			echo "<tr><td>$nombre_medico</td><td>$espe_med</td><td align='center'>$cat_med</td><td align='center'>$frec_grilla</td><td align='center'>$num_veces_medico</td><td>$cadena_dias</td></tr>";
			//break;
		}
	}
	echo "</table>";
}
?>