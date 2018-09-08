<?php
require("conexion.inc");
require("estilos_administracion.inc");
?>
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
	ajax.open('GET', 'ajaxPromotores.php?codTerritorio='+codTerritorio+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function envia_formulario(f)
{	var rpt_fechaini, rpt_fechafin, rpt_ver;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	rpt_ver=f.rpt_ver.value;
	
	var rpt_promotor=new Array();
	var rpt_linea=new Array();
	var rpt_canal=new Array();
	
	rpt_fechaini=f.fecha_inicio.value;
	rpt_fechafin=f.fecha_final.value;
	
	var j=0;	
	for(i=0;i<=f.rpt_promotor.options.length-1;i++)
	{	if(f.rpt_promotor.options[i].selected)
		{	rpt_promotor[j]=f.rpt_promotor.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_linea.options.length-1;i++)
	{	if(f.rpt_linea.options[i].selected)
		{	rpt_linea[j]=f.rpt_linea.options[i].value;
			j++;
		}
	}
	j=0;
	for(i=0;i<=f.rpt_canal.options.length-1;i++)
	{	if(f.rpt_canal.options[i].selected)
		{	rpt_canal[j]=f.rpt_canal.options[i].value;
			j++;
		}
	}
	window.open('rptParetoClientes.php?rpt_promotor='+rpt_promotor+'&rpt_linea='+rpt_linea+'&rpt_territorio='+rpt_territorio+'&rpt_fechaini='+rpt_fechaini+'&rpt_fechafin='+rpt_fechafin+'&rpt_canal='+rpt_canal+'&rpt_ver='+rpt_ver,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	return(true);
}

</script>
<?php


echo "<center><h1>Reporte Pareto de Clientes</h1><br>";
echo"<form method='post'>";

	echo"\n<table class='texto' width='30%'>\n";		
	echo "<tr><th align='left'>Ciudad</th>
	<td>
	<select name='rpt_territorio' class='texto' onChange='ajaxVisitadores(this);' size='12'>";
	$sql="select c.cod_ciudad, c.nombre_ciudad from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.nombre_ciudad";
				
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Promotor Asignado</th>";
	echo "<td><div id='divVisitadores'></div></td></tr>";

	echo "<tr><th align='left'>Linea</th>
	<td>
	<select name='rpt_linea' class='texto' size='10' multiple>";
	$sql="select distinct(p.linea) from productos p order by 1";			
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$nombreLinea=$dat[0];
		echo "<option value='`$nombreLinea`'>$nombreLinea</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Canal</th>
	<td>
	<select name='rpt_canal' class='texto' size='5' multiple>";
	$sql="select distinct(c.canal) from ventas c order by 1";			
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$nombreCanal=$dat[0];
		echo "<option value='`$nombreCanal`'>$nombreCanal</option>";
	}
	echo "</select></td></tr>";
	
	$sqlFechas="select min(v.fecha_venta), max(v.fecha_venta) from ventas v;";
	$respFechas=mysql_query($sqlFechas);
	$fechaIni=mysql_result($respFechas,0,0);
	$fechaFin=mysql_result($respFechas,0,1);
	
	echo "<tr><th align='left'>Mes Inicio</th>";
	echo "<td><input type='date' name='fecha_inicio' value='$fechaIni' min='$fechaIni'></td></tr>";
	
	echo "<tr><th align='left'>Fecha Fin</th>";
	echo "<td><input type='date' name='fecha_final' value='$fechaFin' max='$fechaFin'></td></tr>";
	
	echo "<tr><th align='left'>Ver: </th>";
	echo "<td><select name='rpt_ver' size='2'>
	<option value='1' selected>Bolivianos</option>
	<option value='2'>Unidades</option>
	</select>
	</td></tr>";
	echo"\n </table><br>";
	
	echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'>
	</center><br>";
	echo"</form>";
?>