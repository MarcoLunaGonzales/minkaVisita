<?php
header("Content-type: application/vnd.ms-excel");
header("Content-Disposition: attachment; filename=archivo.xls");
require("conexion.inc");
require("estilos_reportes_regional.inc");

	$vector_ruteros=explode(",",$_GET["rutero_maestro"]);
	$n=sizeof($vector_ruteros);

	$sql_cab_rutero="select nombre_rutero from rutero_maestro_cab where cod_rutero='$rutero_maestro'";
	$resp_cab_rutero=mysql_query($sql_cab_rutero);
	$dat_cab_rutero=mysql_fetch_array($resp_cab_rutero);
	$nombre_rutero_maestro=$dat_cab_rutero[0];
	$sql_visitador="select paterno, materno, nombres
	from funcionarios where codigo_funcionario='$visitador'";
	$resp_visitador=mysql_query($sql_visitador);
	$dat_visitador=mysql_fetch_array($resp_visitador);
	$nombre_visitador="$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	$sql_especialidad="select desc_especialidad from especialidades where cod_especialidad='$parametro'";
	$resp_especialidad=mysql_query($sql_especialidad);
	$dat_espe=mysql_fetch_array($resp_especialidad);
	$nombre_especialidad=$dat_espe[0];
echo "<center><table border='0' class='textotit'><tr><th>Reporte Medicos en Rutero Maestro Consolidado <br> Visitador: $nombre_visitador</th></tr></table></center><br>";
echo "<table class='texto' border='1' cellspacing='0' align='center' width='100%'>";
echo "<tr><td>&nbsp;</td>";
for($i=0;$i<$n;$i++)
{	$rutero_maestro=$vector_ruteros[$i];
	$sql="select r.nombre_rutero, l.nombre_linea from rutero_maestro_cab r, lineas l
	where r.codigo_linea=l.codigo_linea and r.cod_rutero=$rutero_maestro";
	$resp=mysql_query($sql);	
	$dat=mysql_fetch_array($resp);
	$nom_rutero=$dat[0];
	$nom_linea=$dat[1];
	echo "<th colspan='6'>$nom_linea $nom_rutero</th>";	
}
echo "<th>&nbsp;</th></tr>";
echo "<tr><th>Especialidad</th>";
for($i=0;$i<$n;$i++)
{	echo "<th>Cat A</th><th>Cat B</th><th>Total Medicos</th><th>Cont. A</th><th>Cont. B</th><th>Sub-total<br>Contactos</th>";
}
echo "<th>TOTAL<BR>CONTACTOS</th></tr>";
$sql_especialidad="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
$resp_especialidad=mysql_query($sql_especialidad);
$numero_total_medicos=0;
while($dat_espe=mysql_fetch_array($resp_especialidad))
{	$cod_especialidad=$dat_espe[0];
	$desc_especialidad=$dat_espe[1];
	//aqui sacamos para cada rutero maestro
	$numero_total_contactos=0;
	$cadena_mostrar="";
	$cadena_mostrar.="<tr><td>$desc_especialidad</td>";
	$bandera_mostrar=0;
	$total_gral_contactos=0;
	for($i=0;$i<$n;$i++)
	{
		$rutero_maestro=$vector_ruteros[$i];
		$sql_linea="select codigo_linea from rutero_maestro_cab where cod_rutero=$rutero_maestro";
		$resp_linea=mysql_query($sql_linea);
		$dat_linea=mysql_fetch_array($resp_linea);
		$linea_ruteromaestro=$dat_linea[0];
		$sql_medicos="select DISTINCT ( rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med
	from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
	where rmc.cod_rutero=rm.cod_rutero and rmc.cod_rutero='$rutero_maestro' and 
	rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.codigo_linea='$linea_ruteromaestro' 
	and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador' and 
	rmd.cod_especialidad='$cod_especialidad' order by m.ap_pat_med, m.ap_mat_med, m.nom_med";
		$resp_medicos=mysql_query($sql_medicos);
		$num_filas=mysql_num_rows($resp_medicos);
		$numero_total_medicos=$numero_total_medicos+$num_filas;
		$numero_a=0;
		$numero_b=0;
		$cant_contactos_a=0;
		$cant_contactos_b=0;
		while($dat_medicos=mysql_fetch_array($resp_medicos))
		{	$cod_med=$dat_medicos[0];
			$sql_cant_contactos="select rmd.cod_med
			from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd, medicos m
			where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
			m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$linea_ruteromaestro' 
			and rmc.cod_visitador='$visitador' and rmd.cod_especialidad='$cod_especialidad' and 
			rmd.cod_med='$cod_med'";
			$resp_cant_contactos=mysql_query($sql_cant_contactos);
			$num_contactos=mysql_num_rows($resp_cant_contactos);
			//echo "$cod_med $num_contactos<br>";
			$categoria_med=$dat_medicos[4];
			if($categoria_med=="A")
			{	$numero_a++;
				$cant_contactos_a=$cant_contactos_a+$num_contactos;
			}
			if($categoria_med=="B")
			{	$numero_b++;
				$cant_contactos_b=$cant_contactos_b+$num_contactos;			
			}
		}
		if($num_filas!=0)
		{	$total_medicos_espe=$numero_a+$numero_b;
			$total_contactos=$cant_contactos_a+$cant_contactos_b;
			$numero_total_contactos=$numero_total_contactos+$total_contactos;
			$cadena_mostrar.="<td align='center'>$numero_a</td><td align='center'>$numero_b</td><td align='center'>$total_medicos_espe</td><td align='center'>$cant_contactos_a</td><td align='center'>$cant_contactos_b</td><td align='center'>$total_contactos</td>";
			$bandera_mostrar=1;
			$total_gral_contactos=$total_gral_contactos+$numero_total_contactos;
		}
		else
		{	//muestra vacios
			$cadena_mostrar.="<td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>";
		}
	}
	$cadena_mostrar.="<td align='center'>$total_gral_contactos</td></tr>";
	if($bandera_mostrar==1)
	{
		echo $cadena_mostrar;
	}
}
echo "<tr><th>&nbsp;</th>";
$total_gral_medicos=0;
$total_gral_contactos=0;
for($i=0;$i<$n;$i++)
{	$rutero_maestro=$vector_ruteros[$i];
	$sql_linea="select codigo_linea from rutero_maestro_cab where cod_rutero=$rutero_maestro";
	$resp_linea=mysql_query($sql_linea);
	$dat_linea=mysql_fetch_array($resp_linea);
	$linea_ruteromaestro=$dat_linea[0];	
	//sacamos los medicos
	$sql_num_medicos="select DISTINCT(rmd.cod_med) 
	from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
	where rmc.cod_rutero=rm.cod_rutero and rmc.cod_rutero='$rutero_maestro' and 
	rm.cod_contacto=rmd.cod_contacto and rmc.codigo_linea='$linea_ruteromaestro' 
	and rmc.cod_visitador='$visitador' and rmd.cod_visitador='$visitador'";
	$resp_num_medicos=mysql_query($sql_num_medicos);
	$filas_medicos=mysql_num_rows($resp_num_medicos);
	$total_gral_medicos=$total_gral_medicos+$filas_medicos;
	//sacamos los contactos
	$sql_cant_contactos="select rmd.cod_med
	from rutero_maestro_cab rmc, rutero_maestro rm, rutero_maestro_detalle rmd
	where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and 
	rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$linea_ruteromaestro' 
	and rmc.cod_visitador='$visitador'";
	$resp_cant_contactos=mysql_query($sql_cant_contactos);
	$filas_contactos=mysql_num_rows($resp_cant_contactos);
	$total_gral_contactos=$total_gral_contactos+$filas_contactos;		
	echo "<th>&nbsp;</th><th>&nbsp;</th><th>$filas_medicos</th><th>&nbsp;</th><th>&nbsp;</th><th>$filas_contactos</th>";	
}
echo "<th>$total_gral_contactos</th></tr>";
echo "</table>";
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
?>