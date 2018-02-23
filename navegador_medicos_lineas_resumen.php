<?php
error_reporting(0);
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M�dica
 * * @copyright 2006
*/
echo "<script language='Javascript'>
function producto_objetivo(f) {
	var i;
	var j=0;
	var j_cod_med;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				j_cod_med=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j>1) {	
		alert('Debe seleccionar solamente un m�dico.');
	} else {
		if(j==0) {
			alert('Debe seleccionar un m&eacute;dico.');
		} else {
			location.href='producto_objetivo.php?j_cod_med='+j_cod_med+'';
		}
	}
}
function denegar_producto(f) {
	var i;
	var j=0;
	var j_cod_med;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				j_cod_med=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j>1) {	
		alert('Debe seleccionar solamente un m�dico.');
	} else {
		if(j==0) {
			alert('Debe seleccionar un m&eacute;dico.');
		} else {
			location.href='denegar_producto.php?j_cod_med='+j_cod_med+'';
		}
	}
}
function eliminar_medico(f) {	
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
		alert('Debe seleccionar al menos un m&eacute;dico para proceder a su eliminaci&oacute;n.');
	} else {	
		if(confirm('Esta seguro de eliminar estos datos.')) {
			location.href='eliminar_medico_linea.php?datos='+datos+'';
		} else {
			return(false); 
		}

	}
}
function editar_medico(f) {	
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
		alert('Debe seleccionar al menos un m&eacute;dico para editar su especialidad/categor&iacute;a.');
	} else {	
		location.href='editar_medico_linea.php?datos='+datos+'';
	}
}
function editar_perfil(f) {
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
		alert('Debe seleccionar al menos un m&eacute;dico para editar su perfil prescriptivo.');
	} else {	
		location.href='editar_perfilprescriptivo.php?datos='+datos+'';
	}
}
</script> ";
require("conexion.inc");
require("estilos_gerencia.inc");
echo "<form method='post' action='opciones_medico.php'>";

$sql="SELECT distinct m.cod_med,m.ap_pat_med,m.ap_mat_med,m.nom_med, p.nombre_perfil_psicografico from medicos m, categorias_lineas c, perfil_psicografico p where m.cod_ciudad='$global_agencia' and m.cod_med=c.cod_med and c.codigo_linea=$global_linea and p.cod_perfil_psicografico = m.perfil_psicografico_med order by m.ap_pat_med";
// echo $sql;
$resp=mysql_query($sql);
echo "<center><table border='0' class='textotit'><tr><td>M&eacute;dicos de la L&iacute;nea</td></tr></table></center><br>";
$indice_tabla=1;
require("home_regional1.inc");
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Eliminar de la L&iacute;nea' name='eliminar' class='boton' onclick='eliminar_medico(this.form)'></td><td><input type='button' value='Editar Espe/Cat' name='Editar' class='boton' onclick='editar_medico(this.form)'></td><!--<td><input type='button' value='Editar Perfil Prescriptivo' class='boton' onclick='editar_perfil(this.form)'></td>--><!--<td><input type='button' value='Productos Objetivo' class='boton' onclick='producto_objetivo(this.form)'></td>--><td><input type='button' value='Quitar Productos' class='boton' alt='Filtra Productos especificos que no se deseen promocionar a un medico en particular.' onclick='denegar_producto(this.form)'></td></tr></table></center><br>";
echo "<center><table border='1' class='textomini' width='70%' cellspacing='0'>";
echo "<tr><th>&nbsp;</th><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidad/Categoria</th><th>Perfil<br>Prescriptivo</th><th>Productos Objetivo</th><th>Productos Filtrados</th></tr>";
while($dat=mysql_fetch_array($resp)) {
	$cod=$dat[0];
	$pat=$dat[1];
	$mat=$dat[2];
	$nom=$dat[3];
	$abrev_perfil = $dat[4];
		// $sql_perfil="SELECT p.abrev_perfilprescriptivo from perfil_prescriptivo p, medicoslinea_perfilprescriptivo mp where p.codigo_perfilprescriptivo=mp.codigo_perfilprescriptivo and mp.cod_med='$cod' and mp.codigo_linea='$global_linea'";
		// // echo $sql_perfil."<br />";
		// $resp_perfil=mysql_query($sql_perfil);
		// $dat_perfil=mysql_fetch_array($resp_perfil);
		// $abrev_perfil=$dat_perfil[0];
	$nombre_completo="$pat $mat $nom";
	$sql2="SELECT c.cod_especialidad, c.categoria_med from especialidades_medicos e, categorias_lineas c where c.cod_med=e.cod_med and c.cod_med=$cod and c.cod_especialidad=e.cod_especialidad and c.codigo_linea=$global_linea order by c.cod_especialidad";
               // echo $sql2."; <br />";
	$resp2=mysql_query($sql2);
	$especialidad="<table border=0 class='texto' width='80%' align='center'>";
	while($dat2=mysql_fetch_array($resp2)) {
		$espe=$dat2[0];
		$cat=$dat2[1];
		$especialidad="$especialidad<tr><td width='70&'>$espe</td><td width='30&'>$cat</td></tr>";
	}
	$especialidad="$especialidad</table>";
	$sql_obj=mysql_query("SELECT * from productos_objetivo where cod_med='$cod' and codigo_linea='$global_linea'");
	$num_prod_obj=mysql_num_rows($sql_obj);
	$sql_filtro=mysql_query("SELECT * from muestras_negadas where cod_med='$cod' and codigo_linea='$global_linea'");
	$num_prod_filtro=mysql_num_rows($sql_filtro);
	if($num_prod_obj==0){$prod_obj="<img src='imagenes/no.gif'>";}else{$prod_obj="<img src='imagenes/si.gif'>";}
	if($num_prod_filtro==0){$prod_fil="<img src='imagenes/no.gif'>";}else{$prod_fil="<img src='imagenes/si.gif'>";}
	echo "<tr><td align='center'>$indice_tabla</td><td align='center'><input type='checkbox' name='codigos_ciclos' value=$cod></td><td align='center'>$cod</td><td class='texto'>&nbsp;$nombre_completo</td><td align='center'>&nbsp;$especialidad</td><td align='center'>&nbsp;$abrev_perfil</td><td align='center'>$prod_obj</td><td align='center'>$prod_fil</td></tr>";
	$indice_tabla++;
}
echo "</table></center><br>";
require("home_regional1.inc");
echo "<center><table border='0' class='texto'>";
echo "<tr><td><input type='button' value='Eliminar de la L&iacute;nea' name='eliminar' class='boton' onclick='eliminar_medico(this.form)'></td><td><input type='button' value='Editar Espe/Cat' name='Editar' class='boton' onclick='editar_medico(this.form)'></td><!--<td><input type='button' value='Editar Perfil Prescriptivo' class='boton' onclick='editar_perfil(this.form)'></td>--><td><!--<input type='button' value='Productos Objetivo' class='boton' onclick='producto_objetivo(this.form)'></td>--><td><input type='button' value='Quitar Productos' class='boton' alt='Filtra Productos especificos que no se deseen promocionar a un medico en particular.' onclick='denegar_producto(this.form)'></td></tr></table></center>";
echo "</form>";
?>