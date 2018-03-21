<script language='JavaScript'>
function envia_formulario(f)
{	var visitador,rutero_maestro, rpt_linea;
	rpt_linea=f.rpt_linea.value;
	var tipoRuteroRpt=f.tipoRuteroRpt.value;
	var gestionCicloRpt=f.gestionCicloRpt.value;
	var rpt_especialidad=new Array();	
	var rpt_visitador=new Array();

	var j=0;
	for(var i=0;i<=f.rpt_especialidad.options.length-1;i++)
	{	if(f.rpt_especialidad.options[i].selected)
		{	rpt_especialidad[j]=f.rpt_especialidad.options[i].value;
			j++;
		}
	}
	j=0;
	for(var i=0;i<=f.rpt_visitador.options.length-1;i++)
	{	if(f.rpt_visitador.options[i].selected)
		{	rpt_visitador[j]=f.rpt_visitador.options[i].value;
			j++;
		}
	}

	window.open('rpt_medicos_rutero_maestro.php?rpt_visitador='+rpt_visitador+'&tipoRuteroRpt='+tipoRuteroRpt+'&gestionCicloRpt='+gestionCicloRpt+'&rpt_linea='+rpt_linea+'&rpt_especialidad='+rpt_especialidad+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	return(true);
}
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
function ajaxVisitadores(codigo){
	var codTerritorio=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codTerritorio[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor;
	contenedor = document.getElementById('divVisitadores');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxVisitadores.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

</script>

<?php
require("conexion.inc");
require('estilos_gerencia.inc');
echo "<center><table class='textotit'><tr><th>Medicos en Rutero Maestro Detallado</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='0' align='center' cellSpacing='0' width='40%'>\n";
	echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto' onChange='ajaxVisitadores(this)'>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
	$resp=mysql_query($sql);
	echo "<option value=''></option>";
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Visitador</th>";
	echo "<td><div id='divVisitadores'></td></tr>";	
	echo "<tr><th align='left'>Ver:</th><td>
	<select name='tipoRuteroRpt' class='texto'>
		<option value='0'>Rutero Maestro</option>
		<option value='1'>Rutero Maestro Aprobado</option>
	</select></td></tr>";
	
	echo "<tr><th align='left'>Gestion - Ciclo</th><td>
	<select name='gestionCicloRpt' class='texto'>";
	$sql="select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g
				where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{
		$codCiclo=$dat[0];
		$codGestion=$dat[1];
		$nombreGestion=$dat[2];
		echo "<option value='$codCiclo|$codGestion|$nombreGestion'>$codCiclo $nombreGestion</option>";
	}
	echo "</select>";
	echo "</td></tr>";
	
	echo "<tr><th align='left'>Linea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='rpt_linea' class='texto'>";
	while($datos_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
		echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Especialidad</th>";
	$sql="select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad";
	$resp=mysql_query($sql);
	echo "<td><select name='rpt_especialidad' class='texto' size='10' multiple>";
	while($dat=mysql_fetch_array($resp))
	{	$codEspe=$dat[0];
		echo "<option value='`$codEspe`'>$codEspe</option>";
	}
	echo "</select></td></tr>";

	echo"\n </table><br>";
	
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>