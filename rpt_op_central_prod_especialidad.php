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
		{	var rpt_especialidad;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_especialidad=f.rpt_especialidad.value;
			window.open('rpt_central_prod_especialidad.php?linea_rpt='+linea_rpt+'&rpt_especialidad='+rpt_especialidad+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
			return(true);
		}
		function envia_formulario_xls(f)
		{	var rpt_especialidad;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_especialidad=f.rpt_especialidad.value;
			window.open('rpt_central_prod_especialidad_xls.php?linea_rpt='+linea_rpt+'&rpt_especialidad='+rpt_especialidad+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
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
	echo "<form method='post' action='rpt_op_central_prod_especialidad.php'>";
	echo "<table border='0' class='textotit' align='center'>";
	echo "<tr><th>Grilla de Productos x Especialidad</th></tr></table><br>";
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
	echo "<tr><th align='left'>Especialidad</th><td><select name='rpt_especialidad' class='texto'>";
	$sql_espe="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp_espe=mysql_query($sql_espe);
	while($dat_espe=mysql_fetch_array($resp_espe))
	{	$cod_especialidad=$dat_espe[0];
		$desc_especialidad=$dat_espe[1];
		$sql="select m.codigo,m.descripcion,m.presentacion 
			from producto_especialidad p, muestras_medicas m 
			where m.codigo=p.codigo_mm and m.codigo_linea=p.codigo_linea and p.cod_especialidad='$cod_especialidad' and m.codigo_linea='$linea_rpt' order by m.descripcion";
		$resp=mysql_query($sql);
		$num_filas=mysql_num_rows($resp);
		if($num_filas==0)
		{	echo "<option value='$cod_especialidad'>$desc_especialidad (sin productos)</option>";
		}
		else
		{	echo "<option value='$cod_especialidad'>$desc_especialidad ($num_filas productos)</option>";
		}
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