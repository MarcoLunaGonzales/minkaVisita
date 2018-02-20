<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita Médica
 * * @copyright 2005
*/
	echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var rpt_linea_visita;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_linea_visita=f.rpt_linea_visita.value;
			window.open('rpt_central_prod_linea_visita.php?linea_rpt='+linea_rpt+'&rpt_linea_visita='+rpt_linea_visita+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
			return(true);
		}
		function envia_formulario_xls(f)
		{	var rpt_linea_visita;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_linea_visita=f.rpt_linea_visita.value;
			window.open('rpt_central_prod_linea_visita_xls.php?linea_rpt='+linea_rpt+'&rpt_linea_visita='+rpt_linea_visita+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
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
	echo "<form>";
	echo "<table border='0' class='textotit' align='center'>";
	echo "<tr><th>Reporte Productos x Línea de Visita</th></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='30%'>";
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
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
	echo "<tr><th align='left'>Línea de Visita</th><td><select name='rpt_linea_visita' class='texto'>";
	$sql_linea="select codigo_l_visita, nombre_l_visita from lineas_visita where codigo_linea='$linea_rpt' order by nombre_l_visita";
	$resp_linea=mysql_query($sql_linea);
	while($dat_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea=$dat_linea[0];
		$desc_linea=$dat_linea[1];
		echo "<option value='$cod_linea'>$desc_linea</option>";
	}
	echo "</select></td>";
	echo"</tr>";
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