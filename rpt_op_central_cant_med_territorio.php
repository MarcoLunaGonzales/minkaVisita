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

	function envia_formulario(f)
	{	var rpt_territorio;
		rpt_gestion=f.gestion_rpt.value;
		var rpt_ciclo=new Array();
	
		rpt_linea=f.rpt_linea.value;
		rpt_clasi=f.rpt_clasi.value;
		var rpt_territorio=new Array();
		var j=0;
		for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
		{	if(f.rpt_territorio.options[i].selected)
			{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
				j++;
			}
		}
		j=0;
		for(i=0;i<=f.ciclo_rpt.options.length-1;i++)
		{	if(f.ciclo_rpt.options[i].selected)
			{	rpt_ciclo[j]=f.ciclo_rpt.options[i].value;
				j++;
			}
		}

		window.open('rpt_central_cant_med_territorio.php?rpt_gestion='+rpt_gestion+'&rpt_ciclo='+rpt_ciclo+'&rpt_territorio='+rpt_territorio+'&rpt_linea='+rpt_linea+'&rpt_clasi='+rpt_clasi+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');			
		return(true);
	}
</script>

<?php

require("conexion.inc");
require("estilos_gerencia.inc");
echo "<center><table class='textotit'><tr><th>Universo de Medicos</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0'>\n";
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

	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' size='12' class='texto' multiple>";
	$sql="select cod_ciudad, descripcion from ciudades where cod_ciudad<>'115' order by descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='rpt_linea' class='texto' onChange='envia_select(this.form)'>";
	while($datos_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
		if($linea_rpt==$cod_linea_rpt)
		{	echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
		}
		else
		{	echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
		}
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Clasificación</th>
			<td><select name='rpt_clasi' class='texto'>
					<option value='0'>En Ruteros Maestro</option>
					<option value='1'>En Ruteros Maestro Aprobados</option>
					<option value='2'>En Listado Madre</option>
			</select></td>
	</tr>";
	
	echo"</table><br>";
	echo"<table align='center'>";  
	echo"</table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>