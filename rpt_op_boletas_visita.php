<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var gestion_rpt, ciclo_rpt;
			var rpt_territorio;
			rpt_territorio=f.rpt_territorio.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			window.open('rpt_boletas_visita.php?gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
	require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Reporte Boletas de Visita</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Gestión</th>";
	$sql_gestion="select distinct(codigo_gestion), nombre_gestion, estado from gestiones order by codigo_gestion desc";
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
	echo $cod_gestion_rpt;
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Ciclo</th>";
	if($gestion_rpt=="")
	{	$sql_ciclo="select distinct(cod_ciclo), estado from ciclos where codigo_gestion='$cod_gestion_rpt' order by cod_ciclo desc";
	}
	else
	{	$sql_ciclo="select distinct(cod_ciclo), estado from ciclos where codigo_gestion='$gestion_rpt' order by cod_ciclo desc";
	}
	$resp_ciclo=mysql_query($sql_ciclo);
	echo "<td><select name='ciclo_rpt' class='texto'>";
	while($datos_ciclo=mysql_fetch_array($resp_ciclo))
	{	$cod_ciclo_rpt=$datos_ciclo[0];$estado_ciclo_rpt=$datos_ciclo[1];
		if($ciclo_rpt==$cod_ciclo_rpt)
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
	
	echo "<tr><th align='left'>Ver</th><td>
		<select name='rptVer' class='texto'>
			<option value='1' selected>Lineas Activas</option>
			<option value='0,1,2' selected>Todas las lineas</option>
		</select>
	</td></tr>";
	
	echo"\n </table><br>";
	echo"<table align='center'>";  
	echo"</table>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');	
	}
	else
	{	require('home_central.inc');
	}
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";

  
?>