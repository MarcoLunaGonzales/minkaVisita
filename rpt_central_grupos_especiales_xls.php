<?php
header("Content-type: application/vnd.ms-excel"); 
header("Content-Disposition: attachment; filename=archivo.xls"); 	
require("conexion.inc");
require("estilos_reportes_central_xls.inc");	
$global_linea=$linea_rpt;
$bandera=0;
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
if($grupo_especial=="")
{	echo "<center>Grupos Especiales<br>Territorio: $nombre_territorio</center><br>";
	$sql_grupo_especial="select codigo_grupo_especial, nombre_grupo_especial, cod_especialidad from grupo_especial
	where agencia='$rpt_territorio' and codigo_linea='$global_linea' order by nombre_grupo_especial";
	$resp_grupo_especial=mysql_query($sql_grupo_especial);
	echo "<center><table border='1' class='textomini' width='100%' cellspacing='0'>";
	echo "<tr><th>Grupo Especial</th><th>Especialidad</th><th>Medicos del Grupo</th></tr>";	
	while($datos_grupo_especial=mysql_fetch_array($resp_grupo_especial))
	{	$codigo_grupo=$datos_grupo_especial[0];
		$nombre_grupo=$datos_grupo_especial[1];
		$cod_especialidad=$datos_grupo_especial[2];
		$sql="select m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, grupo_especial_detalle gd 
		where m.cod_med=gd.cod_med and gd.codigo_grupo_especial='$codigo_grupo'";
		$resp=mysql_query($sql);
		$medicos_grupo="";
		$medicos_grupo.="<table border=0 width='100%' class='texto'><tr><th>&nbsp;</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$nombre_medico="$dat[0] $dat[1] $dat[2]";
			$medicos_grupo.="<tr><td>$nombre_medico</td></tr>";	
		}
		$medicos_grupo.="</table>";	
		echo "<tr><td align='center'>$nombre_grupo</td><td align='center'>$cod_especialidad</td><td>$medicos_grupo</td></tr>";		
	}
	echo "</table></center><br>";
	$bandera=1;
}
if($grupo_especial!="" and $bandera==0)
{	$sql_grupo_especial="select codigo_grupo_especial, nombre_grupo_especial, cod_especialidad from grupo_especial
	where codigo_grupo_especial='$grupo_especial' and agencia='$rpt_territorio' and codigo_linea='$global_linea'order by nombre_grupo_especial";
	$resp_grupo_especial=mysql_query($sql_grupo_especial);
	while($datos_grupo_especial=mysql_fetch_array($resp_grupo_especial))
	{	$codigo_grupo=$datos_grupo_especial[0];
		$nombre_grupo=$datos_grupo_especial[1];
		$cod_especialidad=$datos_grupo_especial[2];
		echo "<center>Grupos Especiales<br>Grupo: $nombre_grupo Especialidad: $cod_especialidad<br>Territorio: $nombre_territorio</center><br>";
		echo "<center><table border='1' class='textomini' width='40%' cellspacing='0'>";
		echo "<tr><th>Medicos del Grupo</th></tr>";	
		$sql="select m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, grupo_especial_detalle gd 
		where m.cod_med=gd.cod_med and gd.codigo_grupo_especial='$codigo_grupo'";
		$resp=mysql_query($sql);
		$medicos_grupo="";
		$medicos_grupo.="<table border=0 width='100%' class='texto'><tr><th>&nbsp;</th></tr>";
		while($dat=mysql_fetch_array($resp))
		{
			$nombre_medico="$dat[0] $dat[1] $dat[2]";
			$medicos_grupo.="<tr><td>$nombre_medico</td></tr>";	
		}
		$medicos_grupo.="</table>";	
		echo "<tr><td>$medicos_grupo</td></tr>";		
	}
	echo "</table></center><br>";
}
?>