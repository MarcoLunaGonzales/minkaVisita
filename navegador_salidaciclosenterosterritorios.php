<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005 
*/ 
	require("conexion.inc");
	require('estilos_almacenes_central.inc');
	$sql_gestion="select codigo_gestion from gestiones where estado='Activo' and codigo_linea='1007'";
	$resp_gestion=mysql_query($sql_gestion);
	$dat_gestion=mysql_fetch_array($resp_gestion);
	$codigo_gestion=$dat_gestion[0];
	$sql="select * from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<center><table border='0' class='textotit'><tr><th>Salidas para ciclos enteros<br>Ciclo: $codigo_ciclo</th></tr></table></center><br>";
	echo "<center><table border='0' class='textomini'><tr><td>Leyenda:</td><th>Salidas Completas</th><td><img src='imagenes/btn_aceptar.bmp'></td><th>Salidas Incompletas</th><td><img src='imagenes/btn_modificar.png'></td><th>Salidas Pendientes</th><td><img src='imagenes/btn_eliminar.bmp'></td></tr></table></center><br>";
	echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
	echo "<tr><th>&nbsp;</th><th>Territorio</th><th>Visitadores</th></tr>";
	echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th><table border=1 class='texto' width='100%'><tr><th width='5%'>&nbsp;</th><th width='30%'>Línea</th><th width='40%'>Visitador</th><th width='13%'>Muestras</th><th width='12%'>Material</th></tr></table></th></tr>";
	$indice_tabla=1;
	while($dat=mysql_fetch_array($resp))
	{
		$cod_ciudad=$dat[0];
		$desc_ciudad=$dat[1];
		$sql_visitadores="select f.codigo_funcionario, f.paterno, f.materno, f.nombres, fl.codigo_linea, l.nombre_linea
							from funcionarios f, funcionarios_lineas fl, lineas l
							where f.codigo_funcionario=fl.codigo_funcionario and l.codigo_linea=fl.codigo_linea and f.cod_ciudad='$cod_ciudad' and cod_cargo='1011' and estado=1 order by l.nombre_linea, f.paterno, f.materno";
		$resp_visitadores=mysql_query($sql_visitadores);
		$cad_visitadores="<table border=1 width='100%' align='center' class='texto'>";
		$indice_interno=1;
		while($dat_visitadores=mysql_fetch_array($resp_visitadores))
		{	$codigo_visitador=$dat_visitadores[0];
			$nombre_visitador="$dat_visitadores[1] $dat_visitadores[2] $dat_visitadores[3]";
			$codigo_linea=$dat_visitadores[4];
			$nombre_linea=$dat_visitadores[5];
			$sql_estado_salida="select cod_estado from salidas_visitador_ciclo where codigo_funcionario='$codigo_visitador'
								and cod_gestion='$codigo_gestion' and cod_ciclo='$codigo_ciclo' and codigo_linea='$codigo_linea' and grupo_salida='1'";
			$resp_estado_salida=mysql_query($sql_estado_salida);
			$dat_estado_salida=mysql_fetch_array($resp_estado_salida);
			$numero_filas_estado=mysql_num_rows($resp_estado_salida);
			$codigo_estado_salida=$dat_estado_salida[0];
			$sql_estado_salida="select cod_estado from salidas_visitador_ciclo where codigo_funcionario='$codigo_visitador'
								and cod_gestion='$codigo_gestion' and cod_ciclo='$codigo_ciclo' and codigo_linea='$codigo_linea' and grupo_salida='2'";
			$resp_estado_salida=mysql_query($sql_estado_salida);
			$dat_estado_salida=mysql_fetch_array($resp_estado_salida);
			$numero_filas_estado_material=mysql_num_rows($resp_estado_salida);
			$codigo_estado_salida_material=$dat_estado_salida[0];
			if($numero_filas_estado==0)
			{	$color_estado="";
				$cad_visitadores="$cad_visitadores<tr bgcolor='$color_estado'><td width='5%'>$indice_interno</td><td width='30%'>$nombre_linea</td><td width='40%'>$nombre_visitador</td><td width='13%' align='center'><a href='registrar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=1'><img src='imagenes/btn_eliminar.bmp' border='0' alt='Registrar Salida'></a></td>";
				if($numero_filas_estado_material==0)
				{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='registrar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_eliminar.bmp' border='0' alt='Registrar Salida'></a></td></tr>";
				}
				else
				{	if($codigo_estado_salida_material==0)
					{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='modificar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_modificar.png' border=0 alt='Registrar Salida'></a></td></tr>";
					}
					else
					{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><img src='imagenes/btn_aceptar.bmp'></td></tr>";
					}
				}
			}
			else
			{	if($codigo_estado_salida==0)
				{	$cad_visitadores="$cad_visitadores<tr><td width='5%'>$indice_interno</td><td width='30%'>$nombre_linea</td><td width='40%'>$nombre_visitador</td><td width='13%' align='center'><a href='modificar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=1'><img src='imagenes/btn_modificar.png' border=0 alt='Completar Salida'></a></td>";
					if($numero_filas_estado_material==0)
					{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='registrar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_eliminar.bmp' border='0' alt='Registrar Salida'></a></td></tr>";
					}
					else
					{	if($codigo_estado_salida_material==0)
						{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='modificar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_modificar.png' border=0 alt='Registrar Salida'></a></td></tr>";
						}
						else
						{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><img src='imagenes/btn_aceptar.bmp'></td></tr>";
						}
					}
				}
				if($codigo_estado_salida==1)
				{	$cad_visitadores="$cad_visitadores<tr><td width='5%'>$indice_interno</td><td width='30%'>$nombre_linea</td><td width='40%'>$nombre_visitador</td><td width='13%' align='center'><img src='imagenes/btn_aceptar.bmp' border=0></td>";
					if($numero_filas_estado_material==0)
					{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='registrar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_eliminar.bmp' border='0' alt='Registrar Salida'></a></td></tr>";
					}
					else
					{	if($codigo_estado_salida_material==0)
						{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><a href='modificar_salidacicloentero.php?codigo_ciclo=$codigo_ciclo&cod_territorio=$cod_ciudad&cod_visitador=$codigo_visitador&codigo_linea=$codigo_linea&grupo_salida=2'><img src='imagenes/btn_modificar.png' alt='Registrar Salida'></a></td></tr>";
						}
						else
						{	$cad_visitadores= "$cad_visitadores<td width='12%' align='center'><img src='imagenes/btn_aceptar.bmp' border=0></td></tr>";
						}
					}
				}
			}
			$indice_interno++;
		}
		$cad_visitadores="$cad_visitadores</table>";
		echo "<tr><td align='center'>$indice_tabla</td><td>&nbsp;$desc_ciudad</td><td align='center'>$cad_visitadores</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center><br>";
	echo"\n<table align='center'><tr><td><a href='navegador_salidaciclosenteros.php'><img  border='0' src='imagenes/back.png' width='40'></a></td></tr></table>";
?>
