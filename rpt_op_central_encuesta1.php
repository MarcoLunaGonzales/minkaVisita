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
{	var rpt_visitador;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_visitador=new Array();
	var rpt_territorio=new Array();
	var j=0;
	for(i=0;i<=f.rpt_visitador.options.length-1;i++)
	{	if(f.rpt_visitador.options[i].selected)
		{	rpt_visitador[j]=f.rpt_visitador.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			j++;
		}
	}
	window.open('rpt_central_encuesta1.php?rpt_visitador='+rpt_visitador+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	return(true);
}
</script>
<?php
require("conexion.inc");
require("estilos_administracion.inc");

echo "<center><table class='textotit'><tr><th>Cobertura de Encuestas x Visitador 2</th></tr></table><br>";
echo"<form method='post'>";
	echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='30%'>\n";
		
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