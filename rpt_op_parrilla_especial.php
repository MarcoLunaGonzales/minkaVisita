<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var parametro,gestion_rpt, ciclo_rpt;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;;
			window.open('rpt_parrilla_especial.php?linea_rpt='+linea_rpt+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
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
echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Parrillas Especiales</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	echo "<option value=''></option>";
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
	echo "<tr><th align='left'>Gestión</th>";
	$sql_gestion="select distinct(codigo_gestion), nombre_gestion, estado from gestiones";
	$resp_gestion=mysql_query($sql_gestion);
	echo "<td><select name='gestion_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	echo "<option></option>";
	while($datos_gestion=mysql_fetch_array($resp_gestion))
	{	$cod_gestion_rpt=$datos_gestion[0];$nom_gestion_rpt=$datos_gestion[1];$estado_gestion_rpt=$datos_gestion[2];
		if($gestion_rpt==$cod_gestion_rpt)
		{	echo "<option value='$cod_gestion_rpt' selected>$nom_gestion_rpt</option>";
		}
		else
		{	echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
		}
	}
	echo "</select></td>";
	echo "<tr><th align='left'>Ciclo</th>";
	if($gestion_rpt=="")
	{	$sql_ciclo="select distinct(cod_ciclo), estado from ciclos where codigo_gestion='$codigo_gestion' order by cod_ciclo desc";
	}
	else
	{	$sql_ciclo="select distinct(cod_ciclo), estado from ciclos where codigo_gestion='$gestion_rpt' order by cod_ciclo desc";
	}
//	echo $sql_ciclo;
	$resp_ciclo=mysql_query($sql_ciclo);
	echo "<td><select name='ciclo_rpt' class='texto'>";
	while($datos_ciclo=mysql_fetch_array($resp_ciclo))
	{	$cod_ciclo_rpt=$datos_ciclo[0];$estado_ciclo_rpt=$datos_ciclo[1];
		if($cod_ciclo_rpt==$ciclo_rpt)
		{	echo "<option value='$cod_ciclo_rpt' selected>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
		}
		else
		{	echo "<option value='$cod_ciclo_rpt'>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value='0'>Todo</option>";
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
	echo "</table><br>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');
	}
	else
	{	require('home_central.inc');
	}
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo "</form>";
?>