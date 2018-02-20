<?php
require("conexion.inc");
require("estilos_reportes_regional.inc");	
$bandera=0;
if($grupo_especial=="")
{	echo "<center><table border='0' class='textotit'><tr><th>Grupos Especiales</th></tr></table></center><br>";
	$sql_grupo_especial="select codigo_grupo_especial, nombre_grupo_especial, cod_especialidad from grupo_especial
	where agencia='$global_agencia' and codigo_linea='$global_linea' order by nombre_grupo_especial";
	$resp_grupo_especial=mysql_query($sql_grupo_especial);
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Grupo Especial</th><th>Especialidad</th><th>Medicos del Grupo</th></tr>";	
	while($datos_grupo_especial=mysql_fetch_array($resp_grupo_especial))
	{	$codigo_grupo=$datos_grupo_especial[0];
		$nombre_grupo=$datos_grupo_especial[1];
		$cod_especialidad=$datos_grupo_especial[2];
		$sql="select m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, grupo_especial_detalle gd 
		where m.cod_med=gd.cod_med and gd.codigo_grupo_especial='$codigo_grupo'";
		$resp=mysql_query($sql);
		$medicos_grupo="";
		$medicos_grupo.="<table border=1 width='100%' class='texto'><tr><th>Nombre Medico</th><th>Especialidad</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$codigo_medico=$dat[0];
			$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$codigo_medico and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
			$resp2=mysql_query($sql2);
			$especialidad="<table border=0 class='textomini' width='50%' align='center'>";
			while($dat2=mysql_fetch_array($resp2))
			{
				$espe=$dat2[0];
				$cat=$dat2[1];
				$especialidad="$especialidad<tr><td align='left'>$espe</td><td align='center'>$cat</td></tr>";
			}
			$especialidad="$especialidad</table>";
			$nombre_medico="$dat[1] $dat[2] $dat[3]";
			$medicos_grupo.="<tr><td>$nombre_medico</td><td>$especialidad</td></tr>";	
		}
		$medicos_grupo.="</table>";	
		echo "<tr><td align='center'>$nombre_grupo</td><td align='center'>$cod_especialidad</td><td>$medicos_grupo</td></tr>";		
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($grupo_especial!="" and $bandera==0)
{	$sql_grupo_especial="select codigo_grupo_especial, nombre_grupo_especial, cod_especialidad from grupo_especial
	where codigo_grupo_especial='$grupo_especial' and agencia='$global_agencia' and codigo_linea='$global_linea'order by nombre_grupo_especial";
	$resp_grupo_especial=mysql_query($sql_grupo_especial);
	while($datos_grupo_especial=mysql_fetch_array($resp_grupo_especial))
	{	$codigo_grupo=$datos_grupo_especial[0];
		$nombre_grupo=$datos_grupo_especial[1];
		$cod_especialidad=$datos_grupo_especial[2];
		echo "<center><table border='0' class='textotit'><tr><th>Grupos Especiales<br>Grupo: $nombre_grupo Especialidad: $cod_especialidad</th></tr></table></center><br>";
		echo "<center><table border='1' class='textomini' width='40%' cellspacing='0'>";
		echo "<tr><th>Medicos del Grupo</th></tr>";	
		$sql="select m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, grupo_especial_detalle gd 
		where m.cod_med=gd.cod_med and gd.codigo_grupo_especial='$codigo_grupo'";
		$resp=mysql_query($sql);
		$medicos_grupo="";
		$medicos_grupo.="<table border=1 width='100%' class='texto'><tr><th>Nombre Medico</th><th>Especialidad</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{	$codigo_medico=$dat[0];
			$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$codigo_medico and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
			$resp2=mysql_query($sql2);
			$nombre_medico="$dat[1] $dat[2] $dat[3]";
			$especialidad="<table border=0 class='textomini' width='50%' align='center'>";
			while($dat2=mysql_fetch_array($resp2))
			{	$espe=$dat2[0];
				$cat=$dat2[1];
				$especialidad="$especialidad<tr><td align='left'>$espe</td><td align='center'>$cat</td></tr>";
			}
			$especialidad="$especialidad</table>";
			$medicos_grupo.="<tr><td align='left'>$nombre_medico</td><td align='center'>$especialidad</td></tr>";	
		}
		$medicos_grupo.="</table>";	
		echo "<tr><td>$medicos_grupo</td></tr>";		
	}
	echo "</table></center><br>";
}

echo "<center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";

?>