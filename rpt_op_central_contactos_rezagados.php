<?php
echo "<script language='JavaScript'>
		function envia_select(form){
			form.submit();
			return(true);
		}
		function envia_formulario(f)
		{	var semana, dia_contacto,visitador,formato;
			var rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			visitador=f.visitador.value;
			semana=f.semana.value;
			formato=f.formato.value;
			dia_contacto=f.dia_contacto.value;
			window.open('rpt_central_contactos_rezagados.php?linea_rpt='+linea_rpt+'&visitador='+visitador+'&semana='+semana+'&dia_contacto='+dia_contacto+'&rpt_territorio='+rpt_territorio+'&formato='+formato+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
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
echo "<center><table class='textotit'><tr><th>Reporte Contactos Rezagados</th></tr></table><br>";
echo"<form metodh='post'>";

	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
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
	where f.cod_cargo=c.cod_cargo and f.cod_cargo='1011' and f.codigo_funcionario=fl.codigo_funcionario and f.estado=1 and fl.codigo_linea='$linea_rpt' and f.cod_ciudad='$rpt_territorio' and f.cod_ciudad=ci.cod_ciudad order by ci.descripcion,f.paterno,c.cargo";
	$resp_visitador=mysql_query($sql_visitador);
	while($dat_visitador=mysql_fetch_array($resp_visitador))
	{	$codigo_visitador=$dat_visitador[0];
		$nombre_visitador="$dat_visitador[1] $dat_visitador[2] $dat_visitador[3]";
		echo "<option value='$codigo_visitador'>$nombre_visitador</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	echo"\n <tr>";
	echo"<th align='left'>Semana</th><td><select name='semana' class='texto'>";
	echo"\n<option value=''>Todo el Ciclo</option>";
	echo"\n<option value='1'>Semana 1</option>";
	echo"\n<option value='2'>Semana 2</option>";
	echo"\n<option value='3'>Semana 3</option>";
	echo"\n<option value='4'>Semana 4</option>";
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	$sql_dias="select id, dia_contacto from orden_dias order by id";
	$resp_dias=mysql_query($sql_dias);
	echo"<th align='left'>Dia de Contacto</th><td><select name='dia_contacto' class='texto'>";
	echo"\n<option value=''>Elija Dia</option>";
	while($dat_dias=mysql_fetch_array($resp_dias))
	{
		$dia_contacto=$dat_dias[1];
		echo"\n <option value='$dia_contacto'>$dia_contacto</option>";
	}
	echo"\n</select></td>";
	echo"\n </tr>";
	echo"\n <tr>";
	echo"<th align='left'>Formato</th><td><select name='formato' class='texto'>";
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
	echo "<table align='center' class='texto' width='80%'><tr><th>Nota: Este reporte se genera a partir de un campo cualquiera. Si tiene mas de un campo seleccionado se generara el reporte a partir del primer campo empezando desde arriba.</th></tr></table>";
	echo"</form>";
?>