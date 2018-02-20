<?php
function cantidad_nacional($tipo_material,$codigo_material,$codigo_ciclo)
{	//$tipo_material=1;
	//$codigo_material="CFMM0094";
	//$codigo_ciclo=15;
	$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, fl.codigo_linea, l.nombre_linea
							from funcionarios f, funcionarios_lineas fl, lineas l
							where f.codigo_funcionario=fl.codigo_funcionario and l.codigo_linea=fl.codigo_linea and cod_cargo='1011' and estado=1 order by l.nombre_linea, f.paterno, f.materno";
	$resp_visitadores=mysql_query($sql_visitadores);
	$cad_visitadores="<table border=1 width='100%' align='center' class='texto'>";
	$suma=0;
	while($dat_visitadores=mysql_fetch_array($resp_visitadores))
	{	$codigo_visitador=$dat_visitadores[0];
		$nombre_visitador="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
		$codigo_linea=$dat_visitadores[4];
		$nombre_linea=$dat_visitadores[5];
		$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med,rmd.cod_especialidad
		from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and 
		rmc.estado_aprobado='1' and rmc.codigo_linea='$codigo_linea' and rmc.cod_visitador='$codigo_visitador'";
		$resp_medicos=mysql_query($sql_medicos);
		$num_filas=mysql_num_rows($resp_medicos);
		$numero_total_medicos=$numero_total_medicos+$num_filas;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
		{	$cod_med=$dat_medicos[0];
			$categoria_med=$dat_medicos[4];
			$cod_especialidad=$dat_medicos[5];
			if($tipo_material==1)
			{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra
					from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$codigo_linea' and p.cod_ciclo='$codigo_ciclo' and 
				p.cod_especialidad='$cod_especialidad' and p.categoria_med='$categoria_med' and pd.codigo_muestra='$codigo_material'";
			}
			else
			{	$sql_parrilla="select pd.codigo_material, pd.cantidad_material, p.categoria_med
				from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$codigo_linea' and p.cod_ciclo='$codigo_ciclo' and 
				p.cod_especialidad='$cod_especialidad' and p.categoria_med='$categoria_med' and pd.codigo_material='$codigo_material'";
			}
			//echo $sql_parrilla."<br>";
			$resp_parrilla=mysql_query($sql_parrilla);
			while($dat_parrilla=mysql_fetch_array($resp_parrilla))
			{	$cantidad_material=$dat_parrilla[1];
				$suma=$suma+$cantidad_material;
			}	
		}
	}
	return($suma);
}

?>