<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var frecuencia,maxima_frecuencia;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			frecuencia=f.frecuencia.value;
			maxima_frecuencia=f.maxima_frecuencia.value;
			window.open('rpt_central_frecuencia_visita.php?linea_rpt='+linea_rpt+'&frecuencia_visita='+frecuencia+'&maxima_frecuencia='+maxima_frecuencia+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		function envia_formulario_xls(f)
		{	var frecuencia,maxima_frecuencia;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			frecuencia=f.frecuencia.value;
			maxima_frecuencia=f.maxima_frecuencia.value;
			window.open('rpt_central_frecuencia_visita_xls.php?linea_rpt='+linea_rpt+'&frecuencia_visita='+frecuencia+'&maxima_frecuencia='+maxima_frecuencia+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_usuario==1052)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
echo "<center><table class='textotit'><tr><th>Reporte Frecuencias de Visita</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo "<tr><th>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	while($datos_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
		if($linea_rpt==$cod_linea_rpt)
		{	echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
		}
		else
		{	echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
		}
	}
	echo "</select></td>";
	echo "<tr><th>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		if($rpt_territorio==$codigo_ciudad)
		{	echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
		}
		else
		{	echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
		}
	}
	echo "</select></td></tr>";
	//estas lineas son auxiliares para el $ciclo_global y el $codigo_gestion
		$sql_gestion=mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo' and codigo_linea='$linea_rpt'");
		$dat_gestion=mysql_fetch_array($sql_gestion);
		$codigo_gestion=$dat_gestion[0];
		$gestion=$dat_gestion[1];
		$sql_ciclo=mysql_query("select cod_ciclo from ciclos where estado='Activo' and codigo_linea='$linea_rpt'");
		$dat_ciclo=mysql_fetch_array($sql_ciclo);
		$ciclo_global=$dat_ciclo[0];

	echo"\n <tr>";
	$sql_medicos_asignados="select m.cod_med from medicos m, categorias_lineas c where m.cod_med=c.cod_med and c.codigo_linea='$linea_rpt' and m.cod_ciudad='$rpt_territorio'";
	$resp_medicos_asignados=mysql_query($sql_medicos_asignados);
	$maxima_frecuencia=0;
	while($dat_medicos_asignados=mysql_fetch_array($resp_medicos_asignados))
	{	$codigo_medico=$dat_medicos_asignados[0];
		$sql_num_frecuencia="select COUNT(rd.cod_med) from rutero r, rutero_detalle rd
		where r.cod_contacto=rd.cod_contacto and r.codigo_linea='$linea_rpt' and r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and rd.cod_med='$codigo_medico'";
		$resp_num_frecuencia=mysql_query($sql_num_frecuencia);
		$dat_frecuencia=mysql_fetch_array($resp_num_frecuencia);
		$frecuencia_medico=$dat_frecuencia[0];
		if($frecuencia_medico>$maxima_frecuencia)
		{	$maxima_frecuencia=$frecuencia_medico;
		}
	}
	echo "<input type='hidden' name='maxima_frecuencia' value='$maxima_frecuencia'>";
	echo"<th>Frecuencia</th><td><select name='frecuencia' class='texto'>";
	echo"<option value=''>Todas las frecuencias</option>";
	for($ii=$maxima_frecuencia;$ii>=1;$ii--)
	{	echo"<option value='$ii'>Frecuencia $ii</option>";
	}
	echo"</select></td>";
	echo"</tr>";
	echo"</table><br>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');
	}
	else
	{	require('home_central.inc');
	}
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>