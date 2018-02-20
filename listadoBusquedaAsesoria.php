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
function buscaMedicos(f, codGestion, codCiclo){
	var nombreMedico=f.nombreMedico.value;
	//alert(nombreMedico);
	var contenedor;
	contenedor = document.getElementById('divDetalle');
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxBuscaMedicoAsesoria.php?nombreMedico='+nombreMedico+'&codGestion='+codGestion+'&codCiclo='+codCiclo,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}else{
			contenedor.innerHTML='Cargando...';
		}
	}
	ajax.send(null)
}
</script>
<?php
	require("conexion.inc");
	require("estilos_regional_pri.inc");

	//$diaContacto=$_GET['diaContacto'];
	$sql_gestion = mysql_query("select codigo_gestion,nombre_gestion from gestiones where estado='Activo'");
	$dat_gestion = mysql_fetch_array($sql_gestion);
	$codGestion = $dat_gestion[0];
	$nombreGestion= $dat_gestion[1];
	$sql_ciclo = mysql_query("select cod_ciclo from ciclos where estado='Activo'");
	$dat_ciclo = mysql_fetch_array($sql_ciclo);
	$codCiclo = $dat_ciclo[0];
	
	/*if($global_usuario==1256 || $global_usuario==1365){
		$codGestion=1013;
		$codCiclo=11;
		$nombreGestion="2016-2017";
	}*/
	
	echo "<form method='post' action=''>";
	echo "<center><table border='0' class='textotit' width='90%'><tr><th>Registro de Asesoria en Consultorio<br>
	Busqueda por Medico<br> Ciclo: $codCiclo - $nombreGestion<br><br>"; 
	
	echo "<input type='text' name='nombreMedico' id='nombreMedico'><input type='button' value='Buscar' onclick='buscaMedicos(this.form,$codGestion, $codCiclo);'>";
	
	echo "<br><br><br>";
	echo "<div id='divDetalle'><center><table border='1' class='texto' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Medico</th><th>Cat. Cod. CUP</th><th>Linea</th><th>Especialidad</th><th>Visitador / Contactos</th></tr>";	
	echo "</table></center></div>";
	
	echo "</form>";
?>