<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var parametro,visitador,gestion_rpt, ciclo_rpt;
			var linea_rpt;
			var dia_inicio_rpt, dia_final_rpt;
			linea_rpt=f.linea_rpt.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;;
			parametro=f.parametro.value;
			visitador=f.visitador.value;
			dia_inicio_rpt=f.dia_inicio.value;
			dia_final_rpt=f.dia_final.value;
			window.open('rpt_regional_maestro_vs_ejecutado.php?linea_rpt='+linea_rpt+'&visitador='+visitador+'&parametro='+parametro+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_dia_inicio='+dia_inicio_rpt+'&rpt_dia_final='+dia_final_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		function envia_formulario_xls(f)
		{	var parametro,visitador,gestion_rpt, ciclo_rpt;
			var linea_rpt;
			var dia_inicio_rpt, dia_final_rpt;
			linea_rpt=f.linea_rpt.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;;
			parametro=f.parametro.value;
			visitador=f.visitador.value;
			dia_inicio_rpt=f.dia_inicio.value;
			dia_final_rpt=f.dia_final.value;
			window.open('rpt_regional_maestro_vs_ejecutado_xls.php?linea_rpt='+linea_rpt+'&visitador='+visitador+'&parametro='+parametro+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_dia_inicio='+dia_inicio_rpt+'&rpt_dia_final='+dia_final_rpt+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
			return(true);
		}
		</script>";
require("conexion.inc");
require("estilos_regional_pri.inc");
require('espacios_regional.inc');
$rpt_territorio=$global_agencia;
echo "<center><table class='textotit'><tr><th>Reporte Rutero Maestro vs. Rutero Ejecutado</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
	$bandera=0;
	echo "<option></option>";
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
			$ban=1;
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
	
	echo "<tr><th align='left'>Inicio</th>";
	echo "<td><select name='dia_inicio' class='texto'>";
	$dias_contacto="select id, dia_contacto from orden_dias order by id";
	$resp_contacto=mysql_query($dias_contacto);
	while($datos_contacto=mysql_fetch_array($resp_contacto))
	{	
		$id_contacto=$datos_contacto[0];$dia_contacto=$datos_contacto[1];
		if($dia_inicio==$id_contacto)
		{	echo "<option value='$id_contacto' selected>$dia_contacto</option>";	
		}
		else
		{	echo "<option value='$id_contacto'>$dia_contacto</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Final</th>";
	echo "<td><select name='dia_final' class='texto'>";
	$dias_contacto="select id, dia_contacto from orden_dias order by id";
	$resp_contacto=mysql_query($dias_contacto);
	while($datos_contacto=mysql_fetch_array($resp_contacto))
	{	
		$id_contacto=$datos_contacto[0];$dia_contacto=$datos_contacto[1];
		if($dia_final==$id_contacto)
		{	echo "<option value='$id_contacto' selected>$dia_contacto</option>";	
		}
		else
		{	echo "<option value='$id_contacto'>$dia_contacto</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Visitador</th><td><select name='visitador' class='texto'>";
	$sql_visitador="select f.codigo_funcionario, f.paterno, f.materno, f.nombres
	from funcionarios f, cargos c, ciudades ci, funcionarios_lineas fl
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$linea_rpt' and f.cod_ciudad='$rpt_territorio' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n <tr>";
	echo"<th align='left'>Formato</th><td><select name='parametro' class='texto'>";
	echo"\n<option value='0'>Resumido</option>";	
	echo"\n<option value='1'>Detallado</option>";	
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n </table><br>";
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
