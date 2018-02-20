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
function ajaxLineas(codigo){
	var codTerritorio=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codTerritorio[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor;
	contenedor = document.getElementById('divLineas');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxLineas.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function envia_formulario(f)
{	var rpt_linea;
	rpt_linea=f.rpt_linea.value;
	var j=0;
	var rpt_territorio=new Array();
	var rpt_territorio1=new Array();
	for(var i=0;i<=f.rpt_territorio.options.length-1;i++)
	{	if(f.rpt_territorio.options[i].selected)
		{	rpt_territorio[j]=f.rpt_territorio.options[i].value;
			rpt_territorio1[j]=f.rpt_territorio.options[i].innerHTML;
			j++;
		}
	}
	window.open('rpt_grilla.php?rpt_territorio='+rpt_territorio+'&rpt_nombreTerritorio='+rpt_territorio1+'&rpt_linea='+rpt_linea+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}
</script>


<?php
	
require("conexion.inc");
require("estilos_gerencia.inc");

echo "<form action='' method='get'>";
	echo "<center><table class='textotit'><tr><th>Grilla de medicos en Rutero Maestro Ultimo Ciclo Vigente</th></tr></table><br>";
	echo "<center><table class='texto' border='1' cellspacing='0'>";
	echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' size='12' multiple onChange='ajaxLineas(this);'>";
	$sql="select c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion";
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Linea</th>";
	echo "<td><div id='divLineas'></td></tr>";

	echo "</table><br>";
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo "</form>";
?>