<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var parametro,gestion_rpt, ciclo_rpt, prod_rpt;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			prod_rpt=f.prod_rpt.value;
			window.open('rpt_parrilla_materialespecialidad.php?linea_rpt='+linea_rpt+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
			return(true);
		}
		function envia_formulario_xls(f)
		{	var parametro,gestion_rpt, ciclo_rpt, prod_rpt;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			prod_rpt=f.prod_rpt.value;
			window.open('rpt_parrilla_materialespecialidad_xls.php?linea_rpt='+linea_rpt+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
			return(true);
		}
		</script>";
require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Material de Apoyo en parrilla por Especialidad</th></tr></table><br>";
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
	$sql_gestion="select codigo_gestion, nombre_gestion, estado from gestiones where codigo_linea='$linea_rpt'";
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
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$linea_rpt' and codigo_gestion='$codigo_gestion' order by cod_ciclo desc";
	}
	else
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$linea_rpt' and codigo_gestion='$gestion_rpt' order by cod_ciclo desc";
	}
	$resp_ciclo=mysql_query($sql_ciclo);
	echo "<td><select name='ciclo_rpt' class='texto' onChange='envia_select(this.form)'>";
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
	echo "<tr><th align='left'>Producto</th>";
	$sql_prod="select distinct(pd.codigo_material), m.descripcion_material
				from parrilla p, parrilla_detalle pd, material_apoyo m
				 where m.codigo_material=pd.codigo_material and p.codigo_parrilla=pd.codigo_parrilla 
				 and p.codigo_linea='$linea_rpt'
				 and p.codigo_gestion='$gestion_rpt' and p.cod_ciclo='$ciclo_rpt' and m.codigo_material<>0";
	$resp_prod=mysql_query($sql_prod);
	echo "<td><select name='prod_rpt' class='texto'>";
	echo "<option value=0>Todos los Materiales</option>";
	while($datos_prod=mysql_fetch_array($resp_prod))
	{	$cod_material=$datos_prod[0];
		$nombre_material=$datos_prod[1];
		echo "<option value='$cod_material'>$nombre_material</option>";
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
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "</form>";
?>