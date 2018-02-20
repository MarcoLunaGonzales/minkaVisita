<script language='JavaScript'>
function envia_formulario(f)
{	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_linea=new Array();
	var rpt_visitador=new Array();
	var rpt_nombreLinea=new Array();
	
	var j=0;
	for(var i=0;i<=f.rpt_visitador.options.length-1;i++)
	{	if(f.rpt_visitador.options[i].selected)
		{	rpt_visitador[j]=f.rpt_visitador.options[i].value;
			j++;
		}
	}

	j=0;
	for(var i=0;i<=f.rpt_linea.options.length-1;i++)
	{	if(f.rpt_linea.options[i].selected)
		{	rpt_linea[j]=f.rpt_linea.options[i].value;
			rpt_nombreLinea[j]=f.rpt_linea.options[i].innerHTML;
			j++;
		}
	}

	window.open('rpt_central_detallemedicos.php?visitador='+rpt_visitador+'&rpt_territorio='+rpt_territorio+'&rpt_linea='+rpt_linea+'&rpt_nombreLinea='+rpt_nombreLinea+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
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
require("estilos_administracion.inc");
echo "<center><table class='textotit'><tr><th>Reporte Detalle de Medicos en Rutero Maestro</th></tr></table><br>";
echo"<form metodh='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='ajaxVisitadores(this)'>";
	$sql="select cod_ciudad, descripcion from ciudades order by descripcion";
	$resp=mysql_query($sql);
    echo "<option value=0>Seleccione una opción</option>";
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
	echo "<tr><th align='left'>Visitador</th>";
	echo "<td><div id='divVisitadores'></td></tr>";
	
	echo "<tr><th align='left'>Línea</th>";
	$sql_linea="select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by nombre_linea";
	$resp_linea=mysql_query($sql_linea);
	echo "<td><select name='rpt_linea' class='texto' size='6' multiple>";
	while($datos_linea=mysql_fetch_array($resp_linea))
	{	$cod_linea_rpt=$datos_linea[0];$nom_linea_rpt=$datos_linea[1];
		echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
	}
	echo "</select></td></tr>";
	
	echo"\n </table><br>";
	echo"<table align='center'>";
	echo"</table>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>
