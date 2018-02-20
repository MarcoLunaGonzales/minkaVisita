<?php
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	$global_linea=$global_linea_distribucion;
	$sql_muestras_material="select DISTINCT(pd.codigo_material) from parrilla p, parrilla_detalle pd, material_apoyo m
   	where p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_material<>0 and pd.codigo_material=m.codigo_material order by m.descripcion_material";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	$indice_m_m=0;
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
		$indice_m_m++;
	}
$sql_verificacion="select * from distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion'
and cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$global_linea' and grupo_trabajo=2";
$resp_verificacion=mysql_query($sql_verificacion);
$num_filas_verificacion=mysql_num_rows($resp_verificacion);
if($num_filas_verificacion==0)
{	$sql_visitador="select fl.codigo_funcionario,f.cod_ciudad from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' and f.estado=1";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	//cereamos la matriz para cada visitador
		for($i=0;$i<=$indice_m_m;$i++)
		{	$matriz_m_m[$i][2]=0;
		}
		$visitador=$dat_visitador[0];
		$cod_territorio=$dat_visitador[1];
		$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp_especialidad=mysql_query($sql_especialidad);
		$numero_total_medicos=0;
		while($dat_espe=mysql_fetch_array($resp_especialidad))
		{	$cod_especialidad=$dat_espe[0];
			$desc_especialidad=$dat_espe[1];
			$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.estado_aprobado='1' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
			$resp_medicos=mysql_query($sql_medicos);
			$num_filas=mysql_num_rows($resp_medicos);
			$numero_total_medicos=$numero_total_medicos+$num_filas;
			$numero_a=0;
			$numero_b=0;
			while($dat_medicos=mysql_fetch_array($resp_medicos))
			{	$cod_med=$dat_medicos[0];
				$sql_cant_contactos="select rmd.cod_med
				from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
				where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' and rmd.cod_med='$cod_med'";
				$resp_cant_contactos=mysql_query($sql_cant_contactos);
				$num_contactos=mysql_num_rows($resp_cant_contactos);
				$categoria_med=$dat_medicos[4];
				if($categoria_med=="A")
				{	$numero_a++;
				}
				if($categoria_med=="B")
				{	$numero_b++;
				}
			}
			//echo "$numero_a $numero_b";
			if($num_filas!=0)
			{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
				from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$global_ciclo_distribucion' and
				p.cod_especialidad='$cod_especialidad'";
				//echo $sql_parrilla;
				$resp_parrilla=mysql_query($sql_parrilla);
				while($dat_parrilla=mysql_fetch_array($resp_parrilla))
				{	$muestra=$dat_parrilla[0]; $cant_muestra=$dat_parrilla[1];
					$material=$dat_parrilla[2];$cant_material=$dat_parrilla[3];
					$categoria_parrilla=$dat_parrilla[4];
					if($categoria_parrilla=="A")
					{	$cantidad_subtotal_muestra=$cant_muestra*$numero_a;
						$cantidad_subtotal_material=$cant_material*$numero_a;
					}
					else
					{	$cantidad_subtotal_muestra=$cant_muestra*$numero_b;
						$cantidad_subtotal_material=$cant_material*$numero_b;
					}
					for($i=0;$i<=$indice_m_m;$i++)
					{	if($matriz_m_m[$i][1]==$muestra)
						{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_subtotal_muestra;
						}
						if($matriz_m_m[$i][1]==$material)
						{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_subtotal_material;
						}
					}
				}
			}
		}
		for($i=0;$i<=$indice_m_m;$i++)
		{	$cod_material=$matriz_m_m[$i][1];
			$cantidad_material=$matriz_m_m[$i][2];
			$inserta_tabla_dist="insert into distribucion_productos_visitadores values('$global_gestion_distribucion','$global_ciclo_distribucion','$cod_territorio','$global_linea','$visitador','$cod_material','$cantidad_material',0,2,0)";
			$resp_tabla_dist=mysql_query($inserta_tabla_dist);
		}
	}
	mysql_query("delete from distribucion_productos_visitadores where codigo_producto=''");

}
echo "<script language='JavaScript'>
		location.href='registro_distribucion_lineasterritorios_ma.php?global_linea=$global_linea';
	</script>";
?>