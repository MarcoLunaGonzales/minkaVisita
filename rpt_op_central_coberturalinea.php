<?php
echo "<script language='JavaScript'>		
		function envia_formulario(f)
		{	var rpt_territorio, rpt_linea;
			rpt_territorio=f.rpt_territorio.value;
			rpt_linea=f.linea_rpt.value;
			rpt_rutero=f.ver_rutero.value;
			window.open('rpt_central_coberturalinea.php?rpt_territorio='+rpt_territorio+'&rpt_linea='+rpt_linea+'&rpt_rutero='+rpt_rutero+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
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
echo "<center><table class='textotit'><tr><th>Cobertura de Medicos Listado Madre Vs. Rutero Maestro</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
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
	echo "<tr><th  align='left'>Línea</th>";
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
	echo "</select></td></tr>";

	echo "<tr><th  align='left'>Ver Ruteros Maestro</th>";
	echo "<td><select name='ver_rutero' class='texto''>";
	echo "<option value='0'>Aprobado</option>";
	echo "<option value='1'>Todo</option>";
	echo "</select></td></tr>";

	echo"</table><br>";
	echo"<table align='center'>";  
	echo"</table>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');	
	}
	else
	{	require('home_central.inc');
	}
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo"</form>";
?>