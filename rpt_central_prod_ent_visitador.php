<?php
$global_linea=$linea_rpt;
$global_visitador=$visitador;
require("conexion.inc");
require("estilos_reportes_central.inc");
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	
if($global_linea==0)
{	
	echo "<center><table border='0' class='textotit'><tr><th>Cantidad de MM y MA Visitadas<br>Territorio: $nombre_territorio<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt Linea: Todas</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='70%' cellspacing='0'>";
	echo "<tr><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Total entregado</th></tr>";
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
	$sqlContactos="select min(r.cod_contacto), MAX(r.cod_contacto) from rutero r, rutero_detalle rd where 
	r.cod_contacto=rd.cod_contacto and r.cod_ciclo='$ciclo_rpt' and r.codigo_gestion='$gestion_rpt'
	and r.cod_visitador in 
	(select codigo_funcionario from funcionarios where cod_ciudad=$rpt_territorio and estado=1 and cod_cargo=1011)";
	$respContactos=mysql_query($sqlContactos);
	$datContactos=mysql_fetch_array($respContactos);
	$minContacto=$datContactos[0];
	$maxContacto=$datContactos[1];
	
		$sql_muestras_material="select distinct(pd.codigo_muestra), m.descripcion, m.presentacion, m.codigo_anterior 
		from parrilla p, parrilla_detalle pd, muestras_medicas m 
		where m.codigo=pd.codigo_muestra and p.codigo_parrilla=pd.codigo_parrilla 
		and p.cod_ciclo='$ciclo_rpt' and p.codigo_gestion='$gestion_rpt' order by m.descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_muestra=$dat_m_m[0];
			$descripcion_muestra=$dat_m_m[1]." ".$dat_m_m[2];
			$codigoAnterior=$dat_m_m[3];

			$sql_cantidad_muestras_entregadas="select sum(cantidad_muestra_entregado), sum(cantidad_muestraextra_entregado)
			from registro_visita rv
			where rv.muestra='$codigo_muestra' and rv.cod_contacto BETWEEN $minContacto and $maxContacto";
			
			$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
			$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
			$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
			$cantidad_extramuestra=$dat_cantidad_muestras_entregadas[1];
			if($cantidad_muestra!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_muestra)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_muestra;
						$matriz_m_m[$i][3]=$matriz_m_m[$i][3]+$cantidad_extramuestra;
					}
				}
			}
		}
		$sql_muestras_material="select distinct(pd.codigo_material), m.descripcion_material 
		from parrilla p, parrilla_detalle pd, material_apoyo m 
		where m.codigo_material=pd.codigo_material and p.codigo_parrilla=pd.codigo_parrilla 
		and p.cod_ciclo='$ciclo_rpt' and p.codigo_gestion='$gestion_rpt' order by m.descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_material=$dat_m_m[0];
			$descripcion_material=$dat_m_m[1];
			$sql_cantidad_material_entregadas="select sum(cantidad_material_entregado), sum(cantidad_materialextra_entregado)
			from registro_visita rv
			where rv.material_apoyo='$codigo_material' and rv.cod_contacto BETWEEN $minContacto and $maxContacto";
			$resp_cantidad_material_entregadas=mysql_query($sql_cantidad_material_entregadas);
			$dat_cantidad_material_entregadas=mysql_fetch_array($resp_cantidad_material_entregadas);
			$cantidad_material=$dat_cantidad_material_entregadas[0];
			$cantidad_extramaterial=$dat_cantidad_material_entregadas[1];
			if($cantidad_material!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_material)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_material;
						$matriz_m_m[$i][3]=$matriz_m_m[$i][3]+$cantidad_extramaterial;
					}
				}
			}
		}
		for($i=0;$i<=$indice_m_m;$i++)
		{	$cod_material=$matriz_m_m[$i][1];
			$sql_nombre_material="select descripcion, codigo_anterior from muestras_medicas where codigo='$cod_material'";
			$resp_nombre_material=mysql_query($sql_nombre_material);
			$filas_material=mysql_num_rows($resp_nombre_material);
			if($filas_material==0)
			{	$sql_nombre_material="select descripcion_material, codigo_material from material_apoyo where codigo_material='$cod_material'";
				$resp_nombre_material=mysql_query($sql_nombre_material);
			}
			$dat_material=mysql_fetch_array($resp_nombre_material);
			$nombre_material=$dat_material[0];
			$codigoAnterior=$dat_material[1];
			
			$cantidad_material=$matriz_m_m[$i][2];
			$cantidad_extra=$matriz_m_m[$i][3];
			$total_entregado=$cantidad_material+$cantidad_extra;
			if($cantidad_material!=0)
			{	echo "<tr><td>$codigoAnterior</td><td align='left'>$nombre_material</td><td align='right'>$cantidad_material </td><td align='right'>$cantidad_extra</td><td align='right'>$total_entregado</td></tr>";
			}
		}
	echo "</table>";
}
if($visitador=="" && $global_linea!=0)
{	echo "<center><table border='0' class='textotit'><tr><th>Cantidad de MM y MA Visitadas<br>Territorio: $nombre_territorio<br>Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='70%' cellspacing='0'>";
	echo "<tr><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Total entregado</th></tr>";
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
			where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' and f.estado=1 and f.cod_ciudad='$rpt_territorio'";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$visitador=$dat_visitador[0];
		$sql_muestras_material="select codigo, descripcion from muestras_medicas order by descripcion";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_muestra=$dat_m_m[0];
			$descripcion_muestra=$dat_m_m[1];
					$sql_cantidad_muestras_entregadas="select sum(cantidad_muestra_entregado), sum(cantidad_muestraextra_entregado)
					from registro_visita rv, rutero_detalle rd, rutero r
					where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
					and r.codigo_gestion='$gestion_rpt' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$visitador' and r.codigo_linea='$linea_rpt' and rv.muestra='$codigo_muestra'";
			$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
			$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
			$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
			$cantidad_extramuestra=$dat_cantidad_muestras_entregadas[1];
			if($cantidad_muestra!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_muestra)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_muestra;
						$matriz_m_m[$i][3]=$matriz_m_m[$i][3]+$cantidad_extramuestra;
					}
				}
			}
		}
		$sql_muestras_material="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
		$resp_muestras_material=mysql_query($sql_muestras_material);
		while($dat_m_m=mysql_fetch_array($resp_muestras_material))
		{	$codigo_material=$dat_m_m[0];
			$descripcion_material=$dat_m_m[1];
					$sql_cantidad_material_entregadas="select sum(cantidad_material_entregado), sum(cantidad_muestraextra_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='$gestion_rpt' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$visitador' and r.codigo_linea='$linea_rpt' and rv.material_apoyo='$codigo_material'";				
			$resp_cantidad_material_entregadas=mysql_query($sql_cantidad_material_entregadas);
			$dat_cantidad_material_entregadas=mysql_fetch_array($resp_cantidad_material_entregadas);
			$cantidad_material=$dat_cantidad_material_entregadas[0];
			$cantidad_extramaterial=$dat_cantidad_material_entregadas[1];
			if($cantidad_material!=0)
			{	for($i=0;$i<=$indice_m_m;$i++)
				{	$cod_material=$matriz_m_m[$i][1];
					if($cod_material==$codigo_material)
					{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+$cantidad_material;
						$matriz_m_m[$i][3]=$matriz_m_m[$i][3]+$cantidad_extramaterial;
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
			$cantidad_extra=$matriz_m_m[$i][3];
			$total_entregado=$cantidad_material+$cantidad_extra;
			if($cantidad_material!=0)
			{	echo "<tr><td>$cod_material</td><td align='left'>$nombre_material</td><td align='right'>$cantidad_material </td><td align='right'>$cantidad_extra</td><td align='right'>$total_entregado</td></tr>";
			}
		}
	echo "</table>";
}
if($visitador!="" && $global_linea!=0)
{	$sql_visitador="select paterno, materno, nombres
	from funcionarios f	where codigo_funcionario='$global_visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	echo "<center><table border='0' class='textotit'><tr><th>Cantidad de MM y MA Visitadas<br>Territorio: $nombre_territorio<br>Visitador $nombre_visitador Gestión: $nombre_cab_gestion Ciclo: $ciclo_rpt</th></tr></table></center><br>";
	echo "<center><table border='1' class='texto' width='80%' cellspacing='0'>";
	echo "<tr><th>Codigo</th><th>Producto</th><th>Cantidad</th><th>Cantidad extra entregada</th><th>Total Entregado</th></tr>";
	$sql_muestras_material="select codigo, descripcion from muestras_medicas order by descripcion";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$codigo_muestra=$dat_m_m[0];
		$descripcion_muestra=$dat_m_m[1];
		$sql_cantidad_muestras_entregadas="select sum(cantidad_muestra_entregado), sum(cantidad_muestraextra_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='$gestion_rpt' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$global_visitador' and r.codigo_linea='$linea_rpt' and rv.muestra='$codigo_muestra'";
		$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
		$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
		$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
		$cantidad_extramuestra=$dat_cantidad_muestras_entregadas[1];
		$total_muestrasentregadas=$cantidad_muestra+$cantidad_extramuestra;
		if($cantidad_muestra!=0)
		{	echo "<tr><td>$codigo_muestra</td><td align='left'>$descripcion_muestra</td><td align='center'>$cantidad_muestra</td><td align='center'>$cantidad_extramuestra</td><td align='center'>$total_muestrasentregadas</td></tr>";
		}
	}
	$sql_muestras_material="select codigo_material, descripcion_material from material_apoyo order by descripcion_material";
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$codigo_muestra=$dat_m_m[0];
		$descripcion_muestra=$dat_m_m[1];
		$sql_cantidad_muestras_entregadas="select sum(cantidad_material_entregado), sum(cantidad_materialextra_entregado)
from registro_visita rv, rutero_detalle rd, rutero r
where r.cod_contacto=rv.cod_contacto and r.cod_contacto=rd.cod_contacto and rd.cod_contacto=rv.cod_contacto and r.cod_ciclo='$ciclo_rpt'
and r.codigo_gestion='$gestion_rpt' and rd.orden_visita=rv.orden_visita and r.cod_visitador='$global_visitador' and r.codigo_linea='$linea_rpt' and rv.material_apoyo='$codigo_muestra'";
		$resp_cantidad_muestras_entregadas=mysql_query($sql_cantidad_muestras_entregadas);
		$dat_cantidad_muestras_entregadas=mysql_fetch_array($resp_cantidad_muestras_entregadas);
		$cantidad_muestra=$dat_cantidad_muestras_entregadas[0];
		$cantidad_extramuestra=$dat_cantidad_muestras_entregadas[1];
		$total_materialentregado=$cantidad_muestra+$cantidad_extramuestra;
		if($cantidad_muestra!=0)
		{	echo "<tr><td>$codigo_muestra</td><td align='left'>$descripcion_muestra</td><td align='center'>$cantidad_muestra</td><td align='center'>$cantidad_extramuestra</td><td align='center'>$total_materialentregado</td></tr>";
		}
	}
	echo "</table>";
}
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>