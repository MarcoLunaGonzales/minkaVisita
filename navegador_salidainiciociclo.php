<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var visitador,gestion_rpt, ciclo_rpt,grupo_salida;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			visitador=f.visitador.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			grupo_salida=f.grupo_salida.value;
			location.href('navegador_salidainiciociclo1.php?linea_rpt='+linea_rpt+'&visitador='+visitador+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_territorio='+rpt_territorio+'&grupo_salida='+grupo_salida+'');			
			return(true);
		}
		</script>";
require("conexion.inc");
require('estilos_almacenes_central.inc');
echo "<center><table class='textotit'><tr><th>Salida para Ciclo Entero</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='40%'>\n";
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
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
	echo $cod_gestion_rpt;
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Ciclo</th>";
	if($gestion_rpt=="")
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$linea_rpt' and codigo_gestion='$cod_gestion_rpt' order by cod_ciclo desc";
	}
	else
	{	$sql_ciclo="select cod_ciclo, estado from ciclos where codigo_linea='$linea_rpt' and codigo_gestion='$gestion_rpt' order by cod_ciclo desc";
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
	echo "<tr><th align='left'>Visitador</th><td><select name='visitador' class='texto'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.codigo_funcionario=fl.codigo_funcionario and f.cod_cargo='1011' and f.estado=1 and fl.codigo_linea='$linea_rpt' and f.cod_ciudad='$rpt_territorio' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo "<tr><th align='left'>Tipo de Salida</th><td><select name='grupo_salida' class='texto'>";
	echo "<option value='1'>Muestras</option>";
	echo "<option value='2'>Material de Apoyo</option>";
	echo "</select>";
	echo "</td></tr>";
	
	echo"\n </table><br>";
	echo"<table align='center'>";  
	echo"</table>";
	require('home_almacen.php');
	//echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='pdf' value='Ver Reporte PDF' onClick='envia_formulario_pdf(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
	echo "<center><input type='button' name='reporte' value='Obtener Pre-Salida' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
?>