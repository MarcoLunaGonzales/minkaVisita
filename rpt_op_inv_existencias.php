<?php
echo "<script language='JavaScript'>
function envia_formulario(f) {	
	var rpt_territorio, rpt_almacen, tipo_item, rpt_ver, rpt_fecha,imagenes_mostrar,rpt_ver1;
	rpt_territorio       = f.rpt_territorio.value;
	rpt_almacen          = f.rpt_almacen.value;
	tipo_item            = f.tipo_item.value;
	var rpt_linea        = new Array();
	rpt_ver              = f.rpt_ver.value;
	rpt_ver1             = f.rpt_ver1.value;
	rpt_fecha            = f.rpt_fecha.value;
	var rpt_tipomaterial = new Array();
	var j = 0;
	for(var i=0;i<=f.rpt_tipomaterial.options.length-1;i++) {	
		if(f.rpt_tipomaterial.options[i].selected) {	
			rpt_tipomaterial[j]=f.rpt_tipomaterial.options[i].value;
			j++;
		}
	}
	j=0;
	for(var i=0;i<=f.rpt_linea.options.length-1;i++) {	
		if(f.rpt_linea.options[i].selected) {	
			rpt_linea[j]=f.rpt_linea.options[i].value;
			j++;
		}
	}
		
	window.open('rpt_inv_existencias.php?rpt_territorio='+rpt_territorio+'&rpt_almacen='+rpt_almacen+'&rpt_linea='+rpt_linea+'&tipo_item='+tipo_item+'&rpt_ver='+rpt_ver+'&rpt_ver1='+rpt_ver1+'&rpt_tipomaterial='+rpt_tipomaterial+'&rpt_fecha='+rpt_fecha+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
	
	return(true);
}
function activa_tipomaterial(f){
	if(f.tipo_item.value==1) {	
		f.rpt_tipomaterial.disabled=true;
	} else {	
		f.rpt_tipomaterial.disabled=false;
	}
}
function envia_select(form){
	form.submit();
	return(true);
}
</script>";
require("conexion.inc");
if ($global_tipoalmacen == 1) {
	require("estilos_almacenes_central.inc");
} else {
	require("estilos_almacenes.inc");
}
$fecha_rptdefault = date("d/m/Y");
echo "<table align='center' class='textotit'><tr><th>Reporte Existencias Almacen x</th></tr></table><br>";
echo"<form method='post' action='' name='form1'>";
echo"\n<table class='texto' border='1' align='center' cellSpacing='0' width='50%'>\n";
echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
if ($global_tipoalmacen == 1) {
	$sql = "SELECT cod_ciudad, descripcion FROM ciudades ORDER BY descripcion";
} else {
	$sql = "SELECT cod_ciudad, descripcion FROM ciudades WHERE cod_ciudad = '$global_agencia' ORDER BY descripcion";
}
$resp = mysql_query($sql);
echo "<option value='0'>Todos</option>";
while ($dat = mysql_fetch_array($resp)) {
	$codigo_ciudad = $dat[0];
	$nombre_ciudad = $dat[1];
	if ($rpt_territorio == $codigo_ciudad) {
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	} else {
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Almacen</th><td><select name='rpt_almacen' class='texto'>";
$sql  = "SELECT cod_almacen, nombre_almacen FROM almacenes WHERE cod_ciudad = '$rpt_territorio'";

$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
	$codigo_almacen = $dat[0];
	$nombre_almacen = $dat[1];
	if ($rpt_almacen == $codigo_almacen) {
		echo "<option value='$codigo_almacen' selected>$nombre_almacen</option>";
	} else {
		echo "<option value='$codigo_almacen'>$nombre_almacen</option>";
	}
}
echo "</select></td></tr>";

echo "<tr><th align='left'>Tipo de Item:</th>";
echo "<td><select name='tipo_item' class='texto' OnChange='activa_tipomaterial(this.form)'>";
echo "<option value='1'>Muestra M&eacute;dica</option>";
echo "<option value='2'>Material de Apoyo</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr><th align='left'>Tipo de Material</th><td><select name='rpt_tipomaterial' class='texto' disabled='true' multiple='6'>";
$sql = "SELECT cod_tipomaterial, nombre_tipomaterial FROM tipos_material ORDER BY nombre_tipomaterial";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
	$cod_tipomaterial    = $dat[0];
	$nombre_tipomaterial = $dat[1];
	if ($rpt_tipomaterial == $cod_tipomaterial) {
		echo "<option value='$cod_tipomaterial' selected>$nombre_tipomaterial</option>";
	} else {
		echo "<option value='$cod_tipomaterial'>$nombre_tipomaterial</option>";
	}
}
echo "</select></td></tr>";

echo "<tr><th align='left'>L&iacute;nea</th><td><select name='rpt_linea' class='texto' multiple size='15'>";
$sql = "SELECT codigo_linea, nombre_linea FROM lineas WHERE linea_inventarios=1 ORDER BY nombre_linea";
$resp = mysql_query($sql);
while ($dat = mysql_fetch_array($resp)) {
	$codigo_linea = $dat[0];
	$nombre_linea = $dat[1];
	if ($rpt_linea == $codigo_linea) {
		echo "<option value='$codigo_linea' selected>$nombre_linea</option>";
	} else {
		echo "<option value='$codigo_linea'>$nombre_linea</option>";
	}
}
$fecha_rptdefault = date("d/m/Y");
echo "</select></td></tr>";
echo "<tr><th align='left'>Ver (existencias):</th>";
echo "<td><select name='rpt_ver' class='texto'>";
echo "<option value='1'>Todo</option>";
echo "<option value='2'>Con Existencia</option>";
echo "<option value='3'>Sin existencia</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr><th align='left'>Ver (Activos-Retirados):</th>";
echo "<td><select name='rpt_ver1' class='texto'>";
echo "<option value='1'>Todo</option>";
echo "<option value='2'>Activos</option>";
echo "<option value='3'>Retirados</option>";
echo "</select>";
echo "</td>";
echo "</tr>";
echo "<tr><th align='left'>Existencias a fecha:</th>";
echo "<TD bgcolor='#ffffff'><INPUT  type='text' class='texto' value='$fecha_rptdefault' id='rpt_fecha' size='10' name='rpt_fecha'>";
echo "<IMG id='imagenFecha' src='imagenes/fecha.bmp'>";
echo "<DLCALENDAR tool_tip='Seleccione la Fecha' ";
echo "daybar_style='background-color: DBE1E7; font-family: verdana; color:000000;' ";
echo "navbar_style='background-color: 7992B7; color:ffffff;' ";
echo "input_element_id='rpt_fecha' ";
echo "click_element_id='imagenFecha'></DLCALENDAR>";
echo "</TD>";
echo "</tr>";

echo "\n</table><br>";
require('home_almacen.php');
echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'><input type='button' name='xls' value='Ver Reporte Excel' onClick='envia_formulario_xls(this.form)' class='boton'></center><br>";
echo "</form>";
echo "<script type='text/javascript' language='javascript' src='dlcalendar.js'></script>";
?>
