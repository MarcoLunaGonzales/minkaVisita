<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=prod_med.xls"); 	
require("conexion.inc");
require("estilos_reportes_regional_xls.inc");
$bandera=0;
if($medico!="" || ($medico=="" && $obj=="" && $filtrado==""))
{	if($medico=="" && $obj=="" && $filtrado=="")
	{	 $sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea order by m.ap_pat_med";
	}
	else
	{	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and m.cod_med='$medico' and c.codigo_linea=$global_linea order by m.ap_pat_med";
	}
	$resp=mysql_query($sql);
	echo "<center>Productos Objetivo y Filtrados x Medico</center><br>";
	$indice_tabla=1;
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th><th>Productos Objetivo</th><th>Productos Filtrados</th></tr>";
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$nombre_completo="$pat $mat $nom";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
		}
		$especialidad="$especialidad<br>";
		$sql_prod_objetivo="select p.codigo_muestra, mm.descripcion, mm.presentacion
						from productos_objetivo p, categorias_lineas c, muestras_medicas mm
						where p.cod_med=c.cod_med and p.cod_med='$cod' and p.codigo_linea='$global_linea' and mm.codigo=p.codigo_muestra
						order by mm.descripcion, mm.presentacion";
		$resp_prod_objetivo=mysql_query($sql_prod_objetivo);
		$cadena_prod_objetivo="";
		$cadena_prod_objetivo="<table class='textomini'>";
		while($dat_prod_objetivo=mysql_fetch_array($resp_prod_objetivo))
		{	$codigo_muestra=$dat_prod_objetivo[0];
			$descripcion=$dat_prod_objetivo[1];
			$presentacion=$dat_prod_objetivo[2];
			$cadena_prod_objetivo="$cadena_prod_objetivo<tr><td>$descripcion $presentacion</td></tr>";
		}
		$cadena_prod_objetivo="$cadena_prod_objetivo</table>";
		$sql_muestras_negadas="select mn.codigo_muestra, mm.descripcion, mm.presentacion
						from muestras_negadas mn, categorias_lineas c, muestras_medicas mm
						where mn.cod_med=c.cod_med and mn.cod_med='$cod' and mn.codigo_linea='$global_linea' and mm.codigo=mn.codigo_muestra
						order by mm.descripcion, mm.presentacion";
		$resp_muestras_negadas=mysql_query($sql_muestras_negadas);
		$cadena_prod_filtrado="";
		$cadena_prod_filtrado="<table class='textomini'>";
		while($dat_prod_filtrado=mysql_fetch_array($resp_muestras_negadas))
		{	$codigo_muestra=$dat_prod_filtrado[0];
			$descripcion=$dat_prod_filtrado[1];
			$presentacion=$dat_prod_filtrado[2];
			$cadena_prod_filtrado="$cadena_prod_filtrado<tr><td>$descripcion $presentacion</td></tr>";
		}
		$cadena_prod_filtrado="$cadena_prod_filtrado</table>";
		
		echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>$nombre_completo</td><td align='center'>&nbsp;$especialidad</td><td align='center'>$cadena_prod_objetivo</td><td align='center'>$cadena_prod_filtrado</td></tr>";
		$indice_tabla++;
		
		
	}
	echo "</table></center><br>";
	$bandera=1;
}

//esta parte saca los medicos dado un producto objetivo
if($obj!="" && $bandera==0)
{	$sql_cab_obj="select descripcion, presentacion from muestras_medicas where codigo='$obj'";
	$resp_cab_obj=mysql_query($sql_cab_obj);
	$dat_cab_obj=mysql_fetch_array($resp_cab_obj);
	$descripcion_prod_obj=$dat_cab_obj[0];	$presentacion_prod_obj=$dat_cab_obj[1];
	echo "<center>Medicos que tienen como producto objetivo: $descripcion_prod_obj $presentacion_prod_obj</center><br>";
	echo "<center><table border='1' class='textomini' width='80%' cellspacing='0'>";
	echo "<tr><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>";		
	$sql_medicos="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c, productos_objetivo p
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and p.cod_med=m.cod_med and p.codigo_muestra='$obj'
		  order by m.ap_pat_med, m.ap_mat_med";
	$resp=mysql_query($sql_medicos);
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$nombre_completo="$pat $mat $nom";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="";
		while($dat2=mysql_fetch_array($resp2))   
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
		}
		$especialidad="$especialidad<br>";
		echo "<tr><td align='center'>$cod</td><td align='center' class='texto'>$nombre_completo</td><td align='center'>&nbsp;$especialidad</td></tr>";
		
		
	}
	echo "</table></center><br>";
	$bandera=1;
}

//esta parte saca los medicos dado un producto filtrado
if($filtrado!="" && $bandera==0)
{	$sql_cab_filtrado="select descripcion, presentacion from muestras_medicas where codigo='$filtrado'";
	$resp_cab_filtrado=mysql_query($sql_cab_filtrado);
	$dat_cab_filtrado=mysql_fetch_array($resp_cab_filtrado);
	$descripcion_prod_filtrado=$dat_cab_filtrado[0];	$presentacion_prod_filtrado=$dat_cab_filtrado[1];
	echo "<center>Medicos que tienen como producto filtrado: $descripcion_prod_filtrado $presentacion_prod_filtrado</center><br>";
	echo "<center><table border='1' class='textomini' width='80%' cellspacing='0'>";
	echo "<tr><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>";		
	$sql_medicos="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med
		 from medicos m, categorias_lineas c, muestras_negadas mn
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and mn.cod_med=m.cod_med and mn.codigo_muestra='$filtrado'
		  order by m.ap_pat_med, m.ap_mat_med";
	$resp=mysql_query($sql_medicos);
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$nombre_completo="$pat $mat $nom";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
		}
		$especialidad="$especialidad<br>";
		echo "<tr><td align='center'>$cod</td><td align='center' class='texto'>$nombre_completo</td><td align='center'>&nbsp;$especialidad</td></tr>";
		
		
	}
	echo "</table></center><br>";
}
?>