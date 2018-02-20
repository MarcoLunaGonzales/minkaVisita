<script language='JavaScript'>
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

function envia_formulario(f)
{	var rpt_visitador,rpt_gestion, rpt_ciclo;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	rpt_gestion=f.gestion_rpt.value;
	var rpt_ciclo=new Array();
	var rpt_nombreciclo=new Array();
	var rpt_visitador=new Array();
	var j=0;
	for(i=0;i<=f.ciclo_rpt.options.length-1;i++)
	{	if(f.ciclo_rpt.options[i].selected)
		{	rpt_ciclo[j]=f.ciclo_rpt.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_visitador.options.length-1;i++)
	{	if(f.rpt_visitador.options[i].selected)
		{	rpt_visitador[j]=f.rpt_visitador.options[i].value;
			j++;
		}
	}
	window.open('rptVerificacionDistNormal.php?rpt_visitador='+rpt_visitador+'&rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	return(true);
}
</script>
<?php
require("conexion.inc");
require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Reporte Verificacion de Distribucion</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
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
		
	echo "<tr><th align='left'>Territorio</th>
	<td>
	<select name='rpt_territorio' class='texto' onChange='ajaxVisitadores(this)' size='12' multiple>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Visitador</th>";
	echo "<td><div id='divVisitadores'></td></tr>";

	echo"\n </table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
	echo"</form>";
?>