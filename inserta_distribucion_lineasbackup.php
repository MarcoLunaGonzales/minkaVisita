<?php
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
	$global_linea=$global_linea_distribucion;
	//PARRILLAS NORMALES
	$sql_muestras_material="select DISTINCT(pd.codigo_muestra) from parrilla p, parrilla_detalle pd, muestras_medicas m
      where p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion' 
      and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and 
      pd.codigo_muestra=m.codigo order by m.descripcion";
	
	echo $sql_muestras_material;
	
	$resp_muestras_material=mysql_query($sql_muestras_material);
	$indice_m_m=0;
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
		//aumentado
		$matriz_m_m[$indice_m_m][3]=1;
		$indice_m_m++;
	}

	//PARRILLAS ESPECIALES
	$sql_muestras_material="select DISTINCT(pd.codigo_muestra) from parrilla_especial p,
	parrilla_detalle_especial pd, muestras_medicas m
    where p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion'
	and p.codigo_linea='$global_linea' and p.codigo_parrilla_especial=pd.codigo_parrilla_especial
	and pd.codigo_muestra=m.codigo order by m.descripcion";
	//echo $sql_muestras_material;
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	//buscamos para insertar
		$bandera=0;
		for($i=0;$i<=$indice_m_m;$i++)
		{	if($dat_m_m[0]==$matriz_m_m[$i][1])
			{	$bandera=1;
			}
		}
		if($bandera==0)
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
				//aumentado
			$matriz_m_m[$indice_m_m][3]=1;
			$indice_m_m++;
		}
	}

	//MATERIAL DE APOYO PARRILLA NORMAL
	$sql_muestras_material="select DISTINCT(pd.codigo_material)
	from parrilla p, parrilla_detalle pd, material_apoyo m
   	where p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion'
	and p.codigo_linea='$global_linea' and p.codigo_parrilla=pd.codigo_parrilla and pd.codigo_material<>0
	and pd.codigo_material=m.codigo_material order by m.descripcion_material";
	//echo $sql_muestras_material;
	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	//buscamos para insertar
		$bandera=0;
		for($i=0;$i<=$indice_m_m;$i++)
		{	if($dat_m_m[0]==$matriz_m_m[$i][1])
			{	$bandera=1;
			}
		}
		if($bandera==0)
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			//aumentado
			$matriz_m_m[$indice_m_m][3]=2;
			$indice_m_m++;
		}
	}

	//MATERIAL DE APOYO PARRILLA ESPECIAL
	$indice_vector=$indice_m_m;
	$sql_muestras_material="select DISTINCT(pd.codigo_material)
	from parrilla_especial p, parrilla_detalle_especial pd, material_apoyo m
   	where p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion'
	and p.codigo_linea='$global_linea' and p.codigo_parrilla_especial=pd.codigo_parrilla_especial and pd.codigo_material<>0
	and pd.codigo_material=m.codigo_material order by m.descripcion_material";

//echo $sql_muestras_material;

	$resp_muestras_material=mysql_query($sql_muestras_material);
	while($dat_m_m=mysql_fetch_array($resp_muestras_material))
	{	//buscamos para insertar
		$bandera=0;
		for($i=0;$i<=$indice_m_m;$i++)
		{	if($dat_m_m[0]==$matriz_m_m[$i][1])
			{	$bandera=1;
			}
		}
		if($bandera==0)
		{	$matriz_m_m[$indice_m_m][1]=$dat_m_m[0];
			//aumentado
			$matriz_m_m[$indice_m_m][3]=2;
			$indice_m_m++;
		}
	}
$sql_verificacion="select * from distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion'
and cod_ciclo='$global_ciclo_distribucion' and codigo_linea='$global_linea' and grupo_salida=1";
$resp_verificacion=mysql_query($sql_verificacion);
$num_filas_verificacion=mysql_num_rows($resp_verificacion);
if($num_filas_verificacion==0)
{	$sql_visitador="select fl.codigo_funcionario,f.cod_ciudad,f.codigo_lineaclave
	from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and
	f.cod_cargo='1011' and f.estado=1 and f.cod_ciudad=102";

	/*$sql_visitador="select fl.codigo_funcionario,f.cod_ciudad,f.codigo_lineaclave
	from funcionarios f, funcionarios_lineas fl
	where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and
	f.cod_cargo='1011' and f.estado=1 and f.cod_ciudad='104'";*/
	
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	//cereamos la matriz para cada visitador
		
		for($i=0;$i<=$indice_m_m;$i++)
		{	$matriz_m_m[$i][2]=0;
		}
		$visitador=$dat_visitador[0];
		$cod_territorio=$dat_visitador[1];
		$linea_clave=$dat_visitador[2];
		$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
		$resp_especialidad=mysql_query($sql_especialidad);
		$numero_total_medicos=0;
		
		echo "entro especialidades";
		
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
			
			echo "entro_medicos";
			
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

if($numero_a!=0 or $numero_b!=0)
{	echo "$cod_especialidad $numero_a $numero_b<br>";
}
			//sacamos los medicos que se encuentran en grupos especiales para disminuir en la cantidad de
			//medicos A y B y despues sacamos las parrillas para los grupos especiales
			$sql_nummedicosgrupoespecial="select count(cl.categoria_med), cl.categoria_med
from grupo_especial g, grupo_especial_detalle gd, categorias_lineas cl,grupo_especial_detalle_visitadores gv
WHERE g.codigo_grupo_especial=gd.codigo_grupo_especial and gd.cod_med=cl.cod_med and g.tipo_grupo=0 and
g.codigo_grupo_especial=gv.codigo_grupo_especial and gv.codigo_funcionario='$visitador' and
g.codigo_linea=cl.codigo_linea and g.cod_especialidad='$cod_especialidad' and g.agencia='$cod_territorio'
and g.codigo_linea='$global_linea' and gd.cod_med in 
       (select rmd.cod_med from rutero_maestro_detalle rmd, rutero_maestro rm, rutero_maestro_cab rmc
       where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and rmc.codigo_linea='$global_linea' 
       and rmc.estado_aprobado=1 and rmc.cod_visitador='$visitador')
	   group by (cl.categoria_med)";

//echo $sql_nummedicosgrupoespecial."<br>";

			$resp_nummedicosgrupoespecial=mysql_query($sql_nummedicosgrupoespecial);
			$filas_nummedicosgrupoespecial=mysql_num_rows($resp_nummedicosgrupoespecial);
			if($filas_nummedicosgrupoespecial>0)
			{	while($dat_nummedgrupoesp=mysql_fetch_array($resp_nummedicosgrupoespecial))
				{	$nummed_especial=$dat_nummedgrupoesp[0];
					$catmed_esp=$dat_nummedgrupoesp[1];

echo "$nummed_especial $catmed_esp Especiales<br>";

					if($catmed_esp=="A")
					{	$numero_a=$numero_a-$nummed_especial;
					}
					if($catmed_esp=="B")
					{	$numero_b=$numero_b-$nummed_especial;
					}
				}
			}
			$sql_grupoespecial="select count(gd.cod_med) as numero_medicos, gd.codigo_grupo_especial
			from grupo_especial g, grupo_especial_detalle gd, grupo_especial_detalle_visitadores gv
			WHERE g.codigo_grupo_especial=gd.codigo_grupo_especial and g.codigo_grupo_especial=gv.codigo_grupo_especial
			and cod_especialidad='$cod_especialidad' and agencia='$cod_territorio' and  
			codigo_linea='$global_linea' and gv.codigo_funcionario='$visitador' 
			and gd.cod_med in 
       		(select rmd.cod_med from rutero_maestro_detalle rmd, rutero_maestro rm, rutero_maestro_cab rmc
       		where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and rmc.codigo_linea='$global_linea' 
       		and rmc.estado_aprobado=1 and rmc.cod_visitador='$visitador')
	   		group BY (g.codigo_grupo_especial)";

//echo $sql_grupoespecial."<br>";

			$resp_grupoespecial=mysql_query($sql_grupoespecial);
			$filas_grupoespecial=mysql_num_rows($resp_grupoespecial);
			if($filas_grupoespecial>0)
			{	while($dat_grupoespecial=mysql_fetch_array($resp_grupoespecial))
				{	$numeromedicosgrupoespecial=$dat_grupoespecial[0];
					$codigogrupoespecial=$dat_grupoespecial[1];

//echo "$numeromedicosgrupoespecial $codigogrupoespecial Grupos Especiales<br>";

					//MUESTRAS
					$sql_parrillaespecial="select pd.codigo_muestra, sum(pd.cantidad_muestra) as cantidad_muestra
					from parrilla_especial p, parrilla_detalle_especial pd
where p.codigo_parrilla_especial=pd.codigo_parrilla_especial and p.cod_ciclo='$global_ciclo_distribucion' and
p.codigo_gestion='$global_gestion_distribucion' and p.cod_especialidad='$cod_especialidad'
and p.codigo_linea='$global_linea' and p.agencia='$cod_territorio'
and p.codigo_grupo_especial='$codigogrupoespecial' group by(pd.codigo_muestra)";
					$resp_parrillaespecial=mysql_query($sql_parrillaespecial);
					$filas_parrillaespecial=mysql_num_rows($resp_parrillaespecial);
					while($dat_parrillaespecial=mysql_fetch_array($resp_parrillaespecial))
					{	$codigoproducto=$dat_parrillaespecial[0];
						$cantidadproducto=$dat_parrillaespecial[1];
						for($i=0;$i<=$indice_m_m;$i++)
						{	if($matriz_m_m[$i][1]==$codigoproducto)
							{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+($cantidadproducto*$numeromedicosgrupoespecial);
							}
						}
					}
					//MATERIAL DE APOYO
					$sql_parrillaespecial="select pd.codigo_material, sum(pd.cantidad_material) as cantidad_muestra from parrilla_especial p, parrilla_detalle_especial pd
where p.codigo_parrilla_especial=pd.codigo_parrilla_especial and p.cod_ciclo='$global_ciclo_distribucion' and
p.codigo_gestion='$global_gestion_distribucion' and p.cod_especialidad='$cod_especialidad'
and p.codigo_linea='$global_linea' and p.agencia='$cod_territorio'
and p.codigo_grupo_especial='$codigogrupoespecial' group by(pd.codigo_muestra)";
					$resp_parrillaespecial=mysql_query($sql_parrillaespecial);
					$filas_parrillaespecial=mysql_num_rows($resp_parrillaespecial);
					while($dat_parrillaespecial=mysql_fetch_array($resp_parrillaespecial))
					{	$codigoproducto=$dat_parrillaespecial[0];
						$cantidadproducto=$dat_parrillaespecial[1];
						for($i=0;$i<=$indice_m_m;$i++)
						{	if($matriz_m_m[$i][1]==$codigoproducto)
							{	$matriz_m_m[$i][2]=$matriz_m_m[$i][2]+($cantidadproducto*$numeromedicosgrupoespecial);
							}
						}
					}
				}
			}
			//echo "$numero_a $numero_b";
			if($num_filas!=0)
			{	//aplicamos una consulta para saber si el visitador hace linea de visita para la especialidad
				$verifica_lineas="select l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores lv
						where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita
						and l.codigo_linea='$global_linea' and lv.codigo_funcionario='$visitador' and le.cod_especialidad='$cod_especialidad'";

//echo "VERIFICA LINEAS $verifica_lineas<br>";

				$resp_verifica_lineas=mysql_query($verifica_lineas);
				//echo "$visitador $verifica_lineas<br>";
				$filas_verifica=mysql_num_rows($resp_verifica_lineas);
				if($filas_verifica!=0)
				{	$dat_linea_visita=mysql_fetch_array($resp_verifica_lineas);
					$linea_visita=$dat_linea_visita[0];
					$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
					from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea'
					and p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion' and
					p.cod_especialidad='$cod_especialidad' and p.codigo_l_visita='$linea_visita'";
					//echo $sql_parrilla."<br>";

//ECHO "PARRILLA POR LINEA DE VISITA";

					$resp_parrilla=mysql_query($sql_parrilla);
				}
				else
				{	$linea_visita=0;
					//si no existe line_visita realizamos la consulta para ver si existen
					//parrillas regionales
					$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material, pd.cantidad_material, p.categoria_med
					from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea'
					and p.cod_ciclo='$global_ciclo_distribucion' and p.codigo_gestion='$global_gestion_distribucion' and
					p.cod_especialidad='$cod_especialidad' and p.codigo_l_visita='$linea_visita' and p.agencia='$cod_territorio'";
					//echo $sql_parrilla."<br>";
					$resp_parrilla=mysql_query($sql_parrilla);
					$filas_parrillaregional=mysql_num_rows($resp_parrilla);

/*if($filas_parrillaregional!=0)
{	ECHO "PARRILLA REGIONAL";
}*/

					//si no existen parrillas regionales se hace la consulta por parrillas globales
					if($filas_parrillaregional==0)
					{	$sql_parrilla="select pd.codigo_muestra, pd.cantidad_muestra, pd.codigo_material,
						pd.cantidad_material, p.categoria_med
						from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and
						p.codigo_linea='$global_linea' and p.cod_ciclo='$global_ciclo_distribucion'
						and p.codigo_gestion='$global_gestion_distribucion'
						and p.cod_especialidad='$cod_especialidad' and p.codigo_l_visita=0 and p.agencia=0";

//echo "PARRILLA GLOBAL<BR>";

						$resp_parrilla=mysql_query($sql_parrilla);
					}
				}
				
				echo "VISITADOR $visitador<br>$sql_parrilla<br>";

				//fin consulta linea_visita y regional
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
			//vemos si es material de apoyo o mm
			$grupo_salida=$matriz_m_m[$i][3];
			//comprobamos linea clave
			if($linea_clave!=$global_linea)
			{	$sql_verifica="select cantidad_planificada from distribucion_productos_visitadores
				where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion'
				and territorio='$cod_territorio' and codigo_linea='$linea_clave' and
				cod_visitador='$visitador' and codigo_producto='$cod_material'";
				$resp_verifica=mysql_query($sql_verifica);
				$num_filas_verifica=mysql_num_rows($resp_verifica);
				if($num_filas_verifica==0)
				{	$inserta_tabla_dist="insert into distribucion_productos_visitadores values('$global_gestion_distribucion','$global_ciclo_distribucion','$cod_territorio','$linea_clave','$visitador','$cod_material','$cantidad_material',0,$grupo_salida,0)";
					$resp_tabla_dist=mysql_query($inserta_tabla_dist);
				}
				else
				{	$dat_verifica=mysql_fetch_array($resp_verifica);
					$cant_planificada=$dat_verifica[0];
					$cant_planificada_actualizada=$cant_planificada+$cantidad_material;
					$sql_update="update distribucion_productos_visitadores set cantidad_planificada='$cant_planificada_actualizada'
					where codigo_gestion='$global_gestion_distribucion' and cod_ciclo='$global_ciclo_distribucion' and territorio='$cod_territorio' and
					codigo_linea='$linea_clave' and codigo_producto='$cod_material' and cod_visitador='$visitador'";
					$resp_update=mysql_query($sql_update);
				}
			}
			else
			{	$inserta_tabla_dist="insert into distribucion_productos_visitadores values('$global_gestion_distribucion','$global_ciclo_distribucion','$cod_territorio','$global_linea','$visitador','$cod_material','$cantidad_material',0,$grupo_salida,0)";
				$resp_tabla_dist=mysql_query($inserta_tabla_dist);
			}
		}
	}
	mysql_query("delete from distribucion_productos_visitadores where codigo_producto=''");
}
echo "<script language='JavaScript'>
		location.href='registro_distribucion_lineasterritorios1.php?global_linea=$global_linea';
	</script>";
?>