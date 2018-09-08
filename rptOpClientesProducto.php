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

function ajaxClientes(codigo){
	var codTerritorio=new Array();
	var j=0;
	for(var i=0;i<=codigo.options.length-1;i++)
	{	if(codigo.options[i].selected)
		{	codTerritorio[j]=codigo.options[i].value;
			j++;
		}
	}
	var contenedor;
	contenedor = document.getElementById('divClientes');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxClientes.php?codTerritorio='+codTerritorio+'',true);
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
	var rpt_cliente=new Array();
	var rpt_linea=new Array();

	rpt_fechaini=f.fecha_inicio.value;
	rpt_fechafin=f.fecha_final.value;
	
	var j=0;	
	for(i=0;i<=f.rpt_cliente.options.length-1;i++)
	{	if(f.rpt_cliente.options[i].selected)
		{	rpt_cliente[j]=f.rpt_cliente.options[i].value;
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
	if(tiporeporte==1){
		window.open('rptClientesProducto.php?rpt_cliente='+rpt_cliente+'&rpt_linea='+rpt_linea+'&rpt_territorio='+rpt_territorio+'&rpt_fechaini='+rpt_fechaini+'&rpt_fechafin='+rpt_fechafin,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}else{
		window.open('rptClientesNOProducto.php?rpt_cliente='+rpt_cliente+'&rpt_linea='+rpt_linea+'&rpt_territorio='+rpt_territorio+'&rpt_fechaini='+rpt_fechaini+'&rpt_fechafin='+rpt_fechafin,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	}
	return(true);
}

</script>
<?php


echo "<center><h1>Reporte Ventas x Cliente Individual</h1><br>";
echo"<form method='post'>";

	echo"\n<table class='texto' width='30%'>\n";		
	echo "<tr><th align='left'>Ciudad</th>
	<td>
	<select name='rpt_territorio' class='texto' onChange='ajaxClientes(this);' size='12'>";
	$sql="select c.cod_ciudad, c.nombre_ciudad from ciudades c, `funcionarios_agencias` f where 
				f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.nombre_ciudad";
				
	$resp=mysql_query($sql);
	while($dat=mysql_fetch_array($resp))
	{	$codigo_ciudad=$dat[0];
		$nombre_ciudad=$dat[1];
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
	echo "</select></td></tr>";
	echo "<tr><th align='left'>Cliente</th>";
	echo "<td><div id='divClientes'></div></td></tr>";

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
	<input type='button' name='reporte' value='Productos que Compra' onClick='envia_formulario(this.form,1)' class='boton'>
	<input type='button' name='reporte' value='Productos que NO Compra' onClick='envia_formulario(this.form,2)' class='boton2'>
	</center><br>";
	echo"</form>";
?>