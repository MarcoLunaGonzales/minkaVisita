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

function ajaxProductos(codigo){
	var codLinea=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codLinea[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor;
	contenedor = document.getElementById('divProductos');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxProductos.php?codLinea='+codLinea+'',true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}

function envia_formulario(f,tiporeporte)
{	var rpt_fechaini, rpt_fechafin;
	var rpt_territorio;
	rpt_territorio=f.rpt_territorio.value;
	var rpt_producto=new Array();
	var rpt_linea=new Array();
	var rpt_canal=new Array();


	rpt_fechaini=f.fecha_inicio.value;
	rpt_fechafin=f.fecha_final.value;
	
	var j=0;	
	for(i=0;i<=f.rpt_producto.options.length-1;i++)
	{	if(f.rpt_producto.options[i].selected)
		{	rpt_producto[j]=f.rpt_producto.options[i].value;
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
	if(tiporeporte==1){
		window.open('rptProductoIndividual.php?rpt_producto='+rpt_producto+'&rpt_linea='+rpt_linea+'&rpt_territorio='+rpt_territorio+'&rpt_fechaini='+rpt_fechaini+'&rpt_fechafin='+rpt_fechafin+'&rpt_canal='+rpt_canal,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}else{
		window.open('rptNOProductoIndividual.php?rpt_producto='+rpt_producto+'&rpt_linea='+rpt_linea+'&rpt_territorio='+rpt_territorio+'&rpt_fechaini='+rpt_fechaini+'&rpt_fechafin='+rpt_fechafin+'&rpt_canal='+rpt_canal,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	return(true);
}

</script>
<?php


echo "<center><h1>Reporte Ventas x Producto Individual</h1><br>";
echo"<form method='post'>";

	echo"\n<table class='texto' width='30%'>\n";		
	echo "<tr><th align='left'>Ciudad</th>
	<td>
	<select name='rpt_territorio' class='texto' size='12'>";
	$sql="select c.cod_ciudad, c.nombre_ciudad from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.nombre_ciudad";
				
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";

	echo "<tr><th align='left'>Linea</th>
	<td>
	<select name='rpt_linea' class='texto' size='10'  onChange='ajaxProductos(this);' multiple>";
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
	$sql="select distinct(v.canal) from ventas v order by 1";			
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$nombreCanal=$dat[0];
		echo "<option value='`$nombreCanal`'>$nombreCanal</option>";
	}
	echo "</select></td></tr>";
	
	echo "<tr><th align='left'>Producto</th>";
	echo "<td><div id='divProductos'></div></td></tr>";

	
	$sqlFechas="select min(v.fecha_venta), max(v.fecha_venta) from ventas v;";
	$respFechas=mysql_query($sqlFechas);
	$fechaIni=mysql_result($respFechas,0,0);
	$fechaFin=mysql_result($respFechas,0,1);
	
	echo "<tr><th align='left'>Mes Inicio</th>";
	echo "<td><input type='date' name='fecha_inicio' value='$fechaIni' min='$fechaIni'></td></tr>";
	
	echo "<tr><th align='left'>Fecha Fin</th>";
	echo "<td><input type='date' name='fecha_final' value='$fechaFin' min='$fechaFin'></td></tr>";
	echo"\n </table><br>";
	
	echo "<center>
	<input type='button' name='reporte' value='Clientes que Compran' onClick='envia_formulario(this.form,1)' class='boton'>
	<input type='button' name='reporte' value='Clientes que NO Compran' onClick='envia_formulario(this.form,2)' class='boton2'>
	</center><br>";
	echo"</form>";
?>