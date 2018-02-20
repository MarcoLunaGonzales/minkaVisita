<?php
$global_agencia=$rpt_territorio;
//$global_linea=$linea_rpt;
require("conexion.inc");
require("estilos_reportes_central.inc");
	$sql_cabecera_gestion=mysql_query("select nombre_gestion from gestiones where codigo_gestion='$gestion_rpt' and codigo_linea='$global_linea'");
	$datos_cab_gestion=mysql_fetch_array($sql_cabecera_gestion);
	$nombre_cab_gestion=$datos_cab_gestion[0];
	$sql_cab="select cod_ciudad,descripcion from ciudades where cod_ciudad='$rpt_territorio'";$resp1=mysql_query($sql_cab);
	$dato=mysql_fetch_array($resp1);
	$nombre_territorio=$dato[1];	
	echo "<center><table class='textotit'><tr><th>Reporte M&eacute;dicos x L&iacute;nea <br>";
	if($linea_rpt!=0)
	{	$sql_lineas="select * from lineas where codigo_linea='$linea_rpt'";
		$resp_lineas=mysql_query($sql_lineas);
		while($dat_lineas=mysql_fetch_array($resp_lineas))
		{	$global_linea=$dat_lineas[0];
			$nombre_linea=$dat_lineas[1];
			echo " L&iacute;nea: $nombre_linea    Territorio: $nombre_territorio </th></tr></table><br>";
		}
	}
	else
	{	echo "Todas las l&iacute;neas  Territorio: $nombre_territorio </th></tr></table><br>";
	}
	
if($linea_rpt==0)
{	$sql_lineas="select * from lineas order by nombre_linea";
}
else
{	$sql_lineas="select * from lineas where codigo_linea='$linea_rpt'";
}
	$resp_lineas=mysql_query($sql_lineas);
	while($dat_lineas=mysql_fetch_array($resp_lineas))
	{	$global_linea=$dat_lineas[0];
		$nombre_linea=$dat_lineas[1];
	
//ORDEN ALFABETICO
if($rpt_vista==0)
{	if($rpt_parametro==0){$orden="asc";}
	else{$orden="desc";}
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
	 from medicos m, categorias_lineas c
	 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea order by m.ap_pat_med $orden";
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		if($linea_rpt==0)
		{	echo "<center><table border='0' class='texto'><tr><th>M%eacute;dicos de la L&iacute;nea $nombre_linea por Orden Alfabetico</th></tr></table></center><br>";
		}
		else
		{	echo "<center><table border='0' class='texto'><tr><th>Orden Alfabetico</th></tr></table></center><br>";
		}
	}	
}
//especialidad
if($rpt_vista==1)
{	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and c.cod_especialidad='$rpt_parametro' order by m.ap_pat_med";
	$resp=mysql_query($sql);
	$sql_cab=mysql_query("select desc_especialidad from especialidades where cod_especialidad='$rpt_parametro'");
	$dat_cab=mysql_fetch_array($sql_cab);
	$especialidad=$dat_cab[0];
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		if($linea_rpt==0)
		{	echo "<center><table border='0' class='texto'><tr><th>L&iacute;nea $nombre_linea Especialidad $especialidad</th></tr></table></center><br>";
		}
		if($linea_rpt!=0)
		{	echo "<center><table border='0' class='texto'><tr><th>Especialidad $especialidad</th></tr></table></center><br>";
		}
	}
}
//ruc
if($rpt_vista==2)
{	if($rpt_parametro==0){$orden="asc";}
	else{$orden="desc";}
	$sql="select distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med,m.fecha_nac_med,m.telf_med,m.telf_celular_med,
		m.email_med,m.hobbie_med,m.estado_civil_med,m.nombre_secre_med,m.perfil_psicografico_med
		 from medicos m, categorias_lineas c
		 where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea order by m.cod_med $orden";
	$resp=mysql_query($sql);
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		if($linea_rpt==0)
		{	echo "<center><table border='0' class='texto'><tr><th>L&iacute;nea $nombre_linea M&eacute;dicos ordenados por RUC</th></tr></table></center><br>";	
		}
		else
		{	echo "<center><table border='0' class='texto'><tr><th>M&eacute;dicos ordenados por RUC</th></tr></table></center><br>";
		}
	}
}
//sacamos los medicos y los listamos
$indice_tabla=1;
	$filas=mysql_num_rows($resp);
	if($filas!=0)
	{
		if($rpt_formato==0)
		{	echo "<center><table border='1' class='textomini' cellspacing='0' width='50%'>";
			echo "<tr><th>&nbsp;</th><th>RUC</th><th>Nombre</th><th>Especialidades</th></tr>";
		}
		else
		{	echo "<center><table border='1' class='textomini' cellspacing='0'>";
			echo "<tr><th>&nbsp;</th><th>RUC</th><th>Nombre</th><th>Nacimiento</th><th>Especialidades</th><th>Direcciones</th><th>Tel&eacute;fonos</th><th>C&eacute;lular</th><th>Correo Electr&oacute;nico</th><th>Secretaria</th><th>Perfil Psicografico</th><th>Estado Civil</th><th>Hobbie</th></tr>";
		}
	}
	while($dat=mysql_fetch_array($resp))
	{
		$cod=$dat[0];
		$pat=$dat[1];
		$mat=$dat[2];
		$nom=$dat[3];
		$fecha_nac=$dat[4];
		$telf=$dat[5];
		$cel=$dat[6];
		$email=$dat[7];
		$hobbie=$dat[8];
		$est_civil=$dat[9];
		$secre=$dat[10];
		$perfil=$dat[11];
		$nombre_completo="$pat $mat $nom";
		$sql1="select direccion, descripcion from direcciones_medicos where cod_med=$cod order by descripcion asc";
		$resp1=mysql_query($sql1);
		$direccion_medico="<table border=0 class='textosupermini' width='100%'>";
			while($dat1=mysql_fetch_array($resp1))
			{
				$dir=$dat1[0];
				$desc_dir=$dat1[1];
				$direccion_medico="$direccion_medico<tr><th>$desc_dir:</th></tr><tr><td align='left'>$dir</td></tr>";
			}
			$direccion_medico="$direccion_medico</table>";
		$sql2="select c.cod_especialidad, c.categoria_med, e.descripcion
      			from especialidades_medicos e, categorias_lineas c
          			where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by e.descripcion";
		$resp2=mysql_query($sql2);
		$especialidad="<table border=0 class='textomini' width='100%'>";
		while($dat2=mysql_fetch_array($resp2))
		{
			$espe=$dat2[0];
			$cat=$dat2[1];
			$desc_espe=$dat2[2];
			$especialidad="$especialidad<tr><td align='left' width='70%'>$espe</td><td align='center' width='30%'>$cat</td></tr>";
		}
		$especialidad="$especialidad</table>";
		if($rpt_formato==0)
		{	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>$nombre_completo</th><td align='center'>&nbsp;$especialidad</th></tr>";
		}
		else
		{	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td><td class='texto'>$nombre_completo</th><td align='center'>&nbsp;$fecha_nac</th><td align='center'>&nbsp;$especialidad</th><td align='center'>&nbsp;$direccion_medico</th><td align='center'>&nbsp;$telf</th><td align='center'>&nbsp;$cel</th><td align='center'>&nbsp;$email</th><td align='center'>&nbsp;$secre</th><td align='center'>&nbsp;$perfil</th><td align='center'>&nbsp;$est_civil</th><td align='center'>&nbsp;$hobbie</th></tr>";
		}
		$indice_tabla++;
	}
	echo "</table></center><br>";
	}
echo "<br><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>";
	
?>