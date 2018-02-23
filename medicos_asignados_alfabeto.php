<?php

require("conexion.inc");
require("estilos_regional_pri.inc");

echo "<script language='Javascript'>
function eliminar_nav(f) {
	var i;
	var j=0;
	datos=new Array();
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				datos[j]=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j==0) {	
		alert('Debe seleccionar al menos un Medico para proceder a su eliminación.');
	} else {
		if(confirm('Esta seguro de eliminar los datos.')) {
			location.href='eliminar_medicos_asignados.php?datos='+datos+'&visitador=$visitador';
		} else {
			return(false);
		}
	}
}
function sel_todo(f) {
	var i;
	var j=0;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.todo.checked==true) {	
				f.elements[i].checked=true;
			} else {	
				f.elements[i].checked=false;
			}

		}
	}
}
</script>";


$sql_cab="SELECT f.paterno, f.materno, f.nombres from funcionarios f where f.codigo_funcionario = '$visitador'";
$resp_cab=mysql_query($sql_cab);
$dat_cab=mysql_fetch_array($resp_cab);
$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";

$sql="SELECT distinct m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med from medicos m, categorias_lineas c,medico_asignado_visitador v where m.cod_ciudad = '$global_agencia' and m.cod_med = c.cod_med and m.cod_med = v.cod_med and v.codigo_visitador = '$visitador'and v.codigo_linea = $global_linea order by m.ap_pat_med";
$resp=mysql_query($sql);
echo "<form>";
echo "<h1>Medicos Asignados<br>Visitador: $nombre_funcionario</h1>";
$indice_tabla=1;

echo"\n<table align='center'><tr><td><a href='navegador_funcionarios_regional.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table><br>";

echo "<center><table class='texto'>";
echo "<tr><td><input type='checkbox' name='todo' onClick='sel_todo(this.form)'>Seleccionar Todo</td></tr></table></center>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";

echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th></tr>";
while($dat=mysql_fetch_array($resp)) {

	$cod=$dat[0];
	$pat=$dat[1];
	$mat=$dat[2];
	$nom=$dat[3];
	$nombre_completo="$pat $mat $nom";
	$sql2="SELECT c.cod_especialidad, c.categoria_med from especialidades_medicos e, categorias_lineas c where c.cod_med = e.cod_med and c.cod_med = $cod and c.cod_especialidad = e.cod_especialidad and c.codigo_linea = $global_linea order by c.cod_especialidad";
	$resp2=mysql_query($sql2);
	$especialidad="";
	while($dat2=mysql_fetch_array($resp2)) {
		$espe=$dat2[0];
		$cat=$dat2[1];
		$especialidad="$especialidad<br>$espe&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;$cat";
	}
	$especialidad="$especialidad<br><br>";
	echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td>&nbsp;$nombre_completo</td><td align='center'>&nbsp;$especialidad</td></tr>";
	$indice_tabla++;
}
echo "</table></center><br>";
echo"\n<table align='center'><tr><td><a href='navegador_funcionarios_regional.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Eliminar' class='boton' onclick='eliminar_nav(this.form)'></td></tr></table></center>";
echo "</form>";
?>