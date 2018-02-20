<?php
echo "<script language='JavaScript'>
function envia_select(form){
	form.submit();
	return(true);
}
function envia_formulario(f) {	
	var parametro,gestion_rpt, ciclo_rpt;
	var rpt_territorio;
	var linea_rpt;
	linea_rpt=f.linea_rpt.value;
	rpt_territorio=f.rpt_territorio.value;
	gestion_rpt=f.gestion_rpt.value;
	ciclo_rpt=f.ciclo_rpt.value;;
	window.open('rpt_contactosxproductoxvisitador.php?linea_rpt='+linea_rpt+'&gestion_rpt='+gestion_rpt+'&ciclo_rpt='+ciclo_rpt+'&rpt_territorio='+rpt_territorio+'','','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=600');
	return(true);
}
</script>";
require("conexion.inc");
if($global_usuario==1052) {	
	require("estilos_gerencia.inc");
} else {	
	require("estilos_inicio_adm.inc");
}
echo "<form action='' method='get'>";
echo "<center><table class='textotit'><tr><th>Reporte Contactos x Producto x Visitador</th></tr></table></center><br>";
echo "<center><table class='texto' border='1' cellspacing='0'>";
echo "<tr><th align='left'>Línea</th>";
$sql_linea  = "SELECT codigo_linea, nombre_linea FROM lineas WHERE linea_promocion = 1 ORDER BY nombre_linea";
$resp_linea = mysql_query($sql_linea);
echo "<td><select name='linea_rpt' class='texto' onChange='envia_select(this.form)'>";
$bandera = 0;
echo "<option value=''></option>";
while( $datos_linea = mysql_fetch_array($resp_linea) ) {	
	$cod_linea_rpt = $datos_linea[0];
	$nom_linea_rpt = $datos_linea[1];
	if($linea_rpt == $cod_linea_rpt) {	
		echo "<option value='$cod_linea_rpt' selected>$nom_linea_rpt</option>";
	} else {	
		echo "<option value='$cod_linea_rpt'>$nom_linea_rpt</option>";
	}
}
echo "</select></td>";
echo "</tr>";
echo "<tr><th align='left'>Gestión</th>";
$sql_gestion  = "SELECT codigo_gestion, nombre_gestion, estado FROM gestiones WHERE codigo_linea = '$linea_rpt'";
$resp_gestion = mysql_query($sql_gestion);
echo "<td><select name='gestion_rpt' class='texto' onChange='envia_select(this.form)'>";
$bandera = 0;
echo "<option></option>";
while( $datos_gestion = mysql_fetch_array($resp_gestion) ) {	
	$cod_gestion_rpt    = $datos_gestion[0];
	$nom_gestion_rpt    = $datos_gestion[1];
	$estado_gestion_rpt = $datos_gestion[2];
	if( $gestion_rpt == $cod_gestion_rpt ) {	
		echo "<option value='$cod_gestion_rpt' selected>$nom_gestion_rpt</option>";
	} else {	
		echo "<option value='$cod_gestion_rpt'>$nom_gestion_rpt</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Ciclo</th>";
if($gestion_rpt=="") {	
	$sql_ciclo="SELECT cod_ciclo, estado FROM ciclos WHERE codigo_linea = '$linea_rpt' AND codigo_gestion = '$codigo_gestion' ORDER BY by cod_ciclo DESC";
} else {	
	$sql_ciclo="SELECT cod_ciclo,estado FROM ciclos WHERE codigo_linea = '$linea_rpt' AND codigo_gestion = '$gestion_rpt' ORDER BY cod_ciclo DESC";
}
$resp_ciclo=mysql_query($sql_ciclo);
echo "<td><select name='ciclo_rpt' class='texto'>";
while($datos_ciclo=mysql_fetch_array($resp_ciclo)) {	
	$cod_ciclo_rpt    = $datos_ciclo[0];
	$estado_ciclo_rpt = $datos_ciclo[1];
	if( $cod_ciclo_rpt == $ciclo_rpt ) {	
		echo "<option value='$cod_ciclo_rpt' selected>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
	} else {	
		echo "<option value='$cod_ciclo_rpt'>$cod_ciclo_rpt ($estado_ciclo_rpt)</option>";
	}
}
echo "</select></td></tr>";
echo "<tr><th align='left'>Territorio</th><td><select name='rpt_territorio' class='texto' onChange='envia_select(this.form)'>";
$sql  = "SELECT cod_ciudad, descripcion FROM ciudades ORDER BY descripcion";
$resp = mysql_query($sql);
while( $dat = mysql_fetch_array($resp) ) {
	$codigo_ciudad = $dat[0];
	$nombre_ciudad = $dat[1];
	if($rpt_territorio == $codigo_ciudad) {	
		echo "<option value='$codigo_ciudad' selected>$nombre_ciudad</option>";
	} else {	
		echo "<option value='$codigo_ciudad'>$nombre_ciudad</option>";
	}
}
echo "</select></td></tr>";
echo "</table></center><br>";
if($global_usuario==1032) {	
	require('home_gerencia.inc');
} else {	
	require('home_central.inc');
}
echo "<center><input type='button' name='reporte' value='Ver Reporte' onClick='envia_formulario(this.form)' class='boton'></center><br>";
echo "</form>";
?>
