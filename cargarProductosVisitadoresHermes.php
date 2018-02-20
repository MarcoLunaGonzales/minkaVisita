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
</script>
<?php
require("conexion.inc");
require("estilos.inc");

echo "<form name='form1' action='guardaCambiarProductoDistribucion.php' method='post'>";

$codCiclo=$_GET['codCiclo'];

echo "<input type='hidden' name='codCicloEnv' value='$codCiclo'>";

echo "<center><table border='0' class='textotit'><tr><td align='center'>Cargar Productos para Funcionarios<br>
Ciclo: <strong>$codCiclo</strong></td></tr></table></center><br>";

echo "<center><table border='1' class='texto' cellspacing='0' width='80%'>";
echo "<tr><th>Seleccione la gestion y ciclo de Visita Medica de la que extraera los datos</th></tr>";
echo "<tr><th>Gestion</th><th>Ciclo</th></tr>";

echo "<tr>";
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
echo "</select></td>";
echo "<td><div id='divCiclos'></td></tr>";

echo "</table></center><br>";

echo "<center><input type='button' class='boton' value='Intercambiar' onClick='enviar(this.form)'></center></form>";
?>