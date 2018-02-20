<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
$global_linea=$linea_rpt;
$global_visitador=$visitador;
require("conexion.inc");
require("estilos_reportes_central.inc");
//borramos la tabla rpt_saldos
$borrar_saldos=mysql_query("delete from rpt_saldos");
//fin borrar
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
if($visitador=="")
{	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Saldo muestras x Visitadores Medicos Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	//formamos la matriz de muestras y material promocional
		$sql_muestras_material="select DISTINCT(pd.codigo_muestra) from parrilla p, parrilla_detalle pd, muestras_medicas m
   	   where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_muestra=m.codigo order by m.descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		$indice_m_m=0;
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
		$sql_muestras_material="select DISTINCT(pd.codigo_material) from parrilla p, parrilla_detalle pd, material_apoyo m
   	   where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_material=m.codigo_material order by m.descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
	//fin formar matriz de muestras y material promocional
	$sql_visitador="select fl.codigo_funcionario from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' and f.estado=1 and f.cod_ciudad='$rpt_territorio'";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$visitador=$dat_visitador[0];
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
			if($num_filas!=0)
			{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
				from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$ciclo_rpt' and 
				p.cod_especialidad='$cod_especialidad'";
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
	}
		for($i=0;$i<=$indice_m_m;$i++)
		{	$cod_material=$matriz_m_m[$i][1];
			$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$filas_material=mysql_num_rows($resp_nombre_material);
			if($filas_material==0)
			{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
				$resp_nombre_material=mysql_query($sql_nombre_material);
			}
			$dat_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_material[0];
			$cantidad_material=$matriz_m_m[$i][2];
			if($cantidad_material!=0)
			{	
				$sql_inserta=mysql_query("insert into rpt_saldos values('$cod_material','$nombre_material','$cantidad_material','0')");
			}
		}
//desde aqui se actualizan las cantidades entregadas
		//formamos la matriz de muestras y material promocional
		$sql_muestras_material="select codigo from muestras_medicas order by descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		$indice_m_m=0;
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
		$sql_muestras_material="select codigo_material from material_apoyo order by descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			$indice_m_m++;
		}
	//fin formar matriz de muestras y material promocional
	$sql_visitador="select fl.codigo_funcionario from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' and f.cod_ciudad='$rpt_territorio'";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$visitador=$dat_visitador[0];
		$sql_muestras_material="select codigo, descripcion from muestras_medicas order by descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_muestra=$dat_m_m[0];
			$descripcion_muestra=$dat_m_m[1];
			$sql_cantidad_muestras_entregadas="select sum(cantidad_muestra_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='1003' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$visitador' and r.codigo_linea='$linea_rpt' and rv.muestra='$codigo_muestra'";
			$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
			$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
			$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
			if($cantidad_muestra!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_muestra)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_muestra;
					}
				}
			}
		}
		$sql_muestras_material="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_material=$dat_m_m[0];
			$descripcion_material=$dat_m_m[1];
			$sql_cantidad_material_entregadas="select sum(cantidad_material_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='1003' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$visitador' and r.codigo_linea='$linea_rpt' and rv.material_apoyo='$codigo_material'";
			$resp_cantidad_material_entregadas=mysql_query($sql_cantidad_material_entregadas);
			$dat_cantidad_material_entregadas=mysql_fetch_array($resp_cantidad_material_entregadas);
			$cantidad_material=$dat_cantidad_material_entregadas[0];
			if($cantidad_material!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_material)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_material;
					}
				}
			}
		}
	}
	for($i=0;$i<=$indice_m_m;$i++)
	{	$cod_material=$matriz_m_m[$i][1];
		$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$filas_material=mysql_num_rows($resp_nombre_material);
		if($filas_material==0)
		{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			$resp_nombre_material=mysql_query($sql_nombre_material);
		}
		$dat_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_material[0];
		$cantidad_material=$matriz_m_m[$i][2];
		if($cantidad_material!=0)
		{	$sql_actualiza=mysql_query("update rpt_saldos set cantidad_entregada='$cantidad_material' where codigo_material='$cod_material'");
		}
	}
//fin actualizar
}
else
{	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Territorio: $nombre_territorio<br>Saldo muestras x Visitadores Medicos<br>Visitador $nombre_visitador Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp_especialidad=mysql_query($sql_especialidad);
	$numero_total_medicos=0;
	//formamos la matriz de muestras y material promocional
	$sql_muestras_material="select DISTINCT(pd.codigo_muestra) from parrilla p, parrilla_detalle pd, muestras_medicas m
      where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_muestra=m.codigo order by m.descripcion";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	$indice_m_m=0;
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
		$indice_m_m++;
	}
	$sql_muestras_material="select DISTINCT(pd.codigo_material) from parrilla p, parrilla_detalle pd, material_apoyo m
      where p.cod_ciclo='$ciclo_rpt' and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_material=m.codigo_material order by m.descripcion_material";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
		$indice_m_m++;
	}
	//fin formar matriz de muestras y material promocional
	while($dat_espe=mysql_fetch_array($resp_especialidad))
	{	$cod_especialidad=$dat_espe[0];
		$desc_especialidad=$dat_espe[1];
		$sql_medicos="select DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
		from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.estado_aprobado='1' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$global_visitador' and rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
		$resp_medicos=mysql_query($sql_medicos);
		$num_filas=mysql_num_rows($resp_medicos);
		$numero_total_medicos=$numero_total_medicos+$num_filas;
		$numero_a=0;
		$numero_b=0;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
		{	$cod_med=$dat_medicos[0];
			$sql_cant_contactos="select rmd.cod_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$global_linea' and rmc.cod_visitador='$global_visitador' and rmd.cod_especialidad='$cod_especialidad' and rmd.cod_med='$cod_med'";
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
		if($num_filas!=0)
		{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
			from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$ciclo_rpt' and 
			p.cod_especialidad='$cod_especialidad'";
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
		$sql_nombre_material="select descripcion from muestras_medicas where codigo='$cod_material'";
		$resp_nombre_material=mysql_query($sql_nombre_material);
		$filas_material=mysql_num_rows($resp_nombre_material);
		if($filas_material==0)
		{	$sql_nombre_material="select descripcion_material from material_apoyo where codigo_material='$cod_material'";
			$resp_nombre_material=mysql_query($sql_nombre_material);
		}
		$dat_material=mysql_fetch_array($resp_nombre_material);
		$nombre_material=$dat_material[0];
		$cantidad_material=$matriz_m_m[$i][2];
		if($cantidad_material!=0)
		{	$sql_inserta=mysql_query("insert into rpt_saldos values('$cod_material','$nombre_material','$cantidad_material','0')");
		}
	}
//se actualiza cantidades entregadas desde aqui
$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	$sql_muestras_material="select codigo, descripcion from muestras_medicas order by descripcion";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$codigo_muestra=$dat_m_m[0];
		$descripcion_muestra=$dat_m_m[1];
		$sql_cantidad_muestras_entregadas="select sum(cantidad_muestra_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='1003' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$global_visitador' and r.codigo_linea='$linea_rpt' and rv.muestra='$codigo_muestra'";
		$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
		$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
		$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
		if($cantidad_muestra!=0)
		{	$sql_actualiza=mysql_query("update rpt_saldos set cantidad_entregada='$cantidad_muestra' where codigo_material='$codigo_muestra'");
		}
	}
	$sql_muestras_material="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$codigo_muestra=$dat_m_m[0];
		$descripcion_muestra=$dat_m_m[1];
		$sql_cantidad_muestras_entregadas="select sum(cantidad_material_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='1003' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$global_visitador' and r.codigo_linea='$linea_rpt' and rv.material_apoyo='$codigo_muestra'";
		$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
		$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
		$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
		if($cantidad_muestra!=0)
		{	$sql_actualiza=mysql_query("update rpt_saldos set cantidad_entregada='$cantidad_muestra' where codigo_material='$codigo_muestra'");
		}
	}
//fin actualizar cantidades entregadas
}
//ejecutamos la consulta mas sencilla del mundo
$sql_reporte="select nombre_material, cantidad_planificada, cantidad_entregada from rpt_saldos order by codigo_material desc,nombre_material asc";
$resp_reporte=mysql_query($sql_reporte);
echo "<center><table border='1' class='texto' width='60%' cellspacing='0'>";
echo "<tr><th>Producto</th><th>Cantidad Planificada</th><th>Cantidad Entregada</th><th>Saldo</th></tr>";
while($dat_reporte=mysql_fetch_array($resp_reporte))
{	$nombre_material=$dat_reporte[0];
	$cant_plani=$dat_reporte[1];
	$cant_entregada=$dat_reporte[2];
	$saldo=$cant_plani-$cant_entregada;
	echo "<tr><td align='left'>$nombre_material</td><td align='center'>$cant_plani</td><td align='center'>$cant_entregada</td><td align='center'>$saldo</td></tr>";	
}
echo "</table>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>