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
		{	var parametro;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			parametro=f.grilla.value;
			window.open('rpt_central_grilla.php?linea_rpt='+linea_rpt+'&codigo_grilla='+parametro+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var parametro;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			parametro=f.grilla.value;
			window.open('rpt_central_grilla_xls.php?linea_rpt='+linea_rpt+'&codigo_grilla='+parametro+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');			
			return(true);
		}
		</script>";
	require("conexion.inc");
	require("estilos_gerencia.inc");
	echo "<form>";
	echo "<table border='0' class='textotit' align='center'>";
	echo "<tr><th>Reporte Grillas</th></tr></table><br>";
	echo "<center><table border='1' cellspacing='0' class='texto' width='30%'>";
	echo "<tr><th>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
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
	
	echo "<tr><th>Nombre Grilla</th><td><select name='grilla' class='texto'>";
	$sql="select codigo_grilla, nombre_grilla, fecha_creacion, fecha_modificacion,estado from grilla where codigo_linea='$linea_rpt' and agencia='$rpt_territorio'";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo=$dat[0];
		$nombre=$dat[1];
		$fecha_creacion=$dat[2];
		$fecha_modi=$dat[3];
		$estado=$dat[4];
		if($estado==0)
		{	$desc_estado="No Vigente";	}
		else
		{	$desc_estado="Vigente";	}	
		echo "<option value='$codigo'>$nombre ($desc_estado)</option>";
	}
	echo "</select></td></tr>";
	echo "</table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	<input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "</form>";
?>