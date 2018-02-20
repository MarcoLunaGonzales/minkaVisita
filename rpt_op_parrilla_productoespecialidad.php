<script language=JavaScript>
function nuevoAjax()
{	var xmlhttp=false;
	try {
			xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
	} catch (e) {
	try {
		xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
	} catch (E) {
		xmlhttp = false;
	}
	}
	if (!xmlhttp && typeof XMLHttpRequest!='undefined') {
 	xmlhttp = new XMLHttpRequest();
	}
	return xmlhttp;
}
function ajaxCiclos(codigo){
	var codGestion=codigo.value;
	var contenedor;
	contenedor = document.getElementById('divCiclos');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxCiclos.php?codGestion='+codGestion+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
		function envia_formulario(f)
		{	var parametro,gestion_rpt, ciclo_rpt, rpt_territorio;
			var linea_rpt;
			linea_rpt=f.linea_rpt.value;
			gestion_rpt=f.gestion_rpt.value;
			ciclo_rpt=f.ciclo_rpt.value;
			rpt_territorio=f.rpt_territorio.value;
			window.open('rpt_parrilla_productoespecialidad.php?linea_rpt='+linea_rpt+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=yes,toolbar=yes,directories=yes,menubar=yes,resizable=yes,width=1000,height=600');
			return(true);
		}
		</script>

<?php

require("conexion.inc");
if($global_usuario==1032)
{	require("estilos_gerencia.inc");
}
else
{	require("estilos_inicio_adm.inc");
}
echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Reporte Productos de parrilla por Especialidad</th></tr></table><br>";
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
	echo "<td><select name='gestion_rpt' class='texto' onChange='ajaxCiclos(this)'>";
	$bandera=0;
	echo "<option></option>";
	while($datos_gestion=mysql_fetch_array($resp_gestion))
	{	$cod_gestion_rpt=$datos_gestion[0];$nom_gestion_rpt=$datos_gestion[1];$estado_gestion_rpt=$datos_gestion[2];
		echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
	}
	echo $cod_gestion_rpt;
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Ciclo</th>";
	echo "<td><div id='divCiclos'></td></tr>";

		echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
	echo "<option value='0'>Parrilla Nacional</option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "</table><br>";
	if($global_usuario==1032)
	{	require('home_gerencia.inc');
	}
	else
	{	require('home_central.inc');
	}
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo "</form>";
?>