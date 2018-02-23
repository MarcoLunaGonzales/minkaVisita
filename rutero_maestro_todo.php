<?php
require("conexion.inc");
require("estilos_visitador.inc");

echo "<script language='Javascript'>
function enviar_nav() {	
	location.href='creacion_rutero_maestro.php?rutero=$rutero';
}
function completar_ciclo() {	
	location.href='completar_rutero_maestro.php?rutero=$rutero';
}
function recuperar_contactos() {	
	location.href='recuperacion_contactos.php?rutero_trabajo=$rutero';
}
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
		alert('Debe seleccionar al menos un contacto para proceder a su eliminación.');
	} else {
		if(confirm('Esta seguro de eliminar los datos.')) {
			location.href='eliminar_rutero_maestro.php?datos='+datos+'&rutero=$rutero';
		} else {
			return(false);
		}
	}
}
function editar_nav(f) {
	var i;
	var j=0;
	var j_contacto;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				j_contacto=f.elements[i].value;
				j=j+1;
			}
		}
	}
	if(j>1) {
		alert('Debe seleccionar solamente un contacto para editar sus datos.');
	} else {
		if(j==0) {
			alert('Debe seleccionar un contacto para editar sus datos.');
		} else {
			location.href='editar_rutero_maestro.php?j_contacto='+j_contacto+'&rutero=$rutero';
		}
	}
}
</script>";

//$global_linea;
//$global_visitador;

echo "<form method='post' action='opciones_medico.php'>";
if($vista==2){
	$nombreTabla="rutero_maestro_cab_aprobado";
	$nombreTabla1="rutero_maestro_aprobado";
	$nombreTabla2="rutero_maestro_detalle_aprobado";
}else{
	$nombreTabla="rutero_maestro_cab";
	$nombreTabla1="rutero_maestro";
	$nombreTabla2="rutero_maestro_detalle";
}
$sql="SELECT r.cod_contacto, r.cod_rutero, r.cod_visitador, r.dia_contacto, r.turno, r.zona_viaje from $nombreTabla1 r, orden_dias o where r.cod_visitador=$global_visitador and r.cod_rutero='$rutero'and r.dia_contacto=o.dia_contacto order by o.id, r.turno";
$resp   =mysql_query($sql);
$filas_rutero=mysql_num_rows($resp);
$sql_nom_rutero=mysql_query("SELECT nombre_rutero from $nombreTabla where cod_rutero='$rutero' and cod_visitador='$global_visitador'");
$dat_nom_rutero=mysql_fetch_array($sql_nom_rutero);
$nombre_rutero=$dat_nom_rutero[0];

echo "<h1>Registro de Rutero Maestro<br>Rutero: $nombre_rutero</h1>";

echo "<table border='0' class='textomini'>
<tr><th>Leyenda: </th><th>Medico con Frecuencia Reducida</th><th bgcolor='#AAAAFF' width='30%'></th></tr>
</table>";

echo"<table align='center'><tr><td><a href='navegador_rutero_maestro.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

if($aprobado!=1) {	
	echo "<div class='divBotones'>";
	if($filas_rutero==0 and $global_zona_viaje==0) {	
		echo "<input type='button' name='replicar' value='Replicar Rutero' class='boton' onclick='recuperar_contactos()'>";
	}
	echo "&nbsp;<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
			<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
			<input type='button' value='Completar 1/2 ciclo' class='boton' onclick='completar_ciclo(this.form)'>
			<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		</div>";
}

echo "<center><table class='texto'>";
if($aprobado!=1) {	
	echo "<tr><th>&nbsp;</th><th>Dia Contacto</th><th>Turno</th><th>Contactos</th></tr>";
} else {
	echo "<tr><th>Dia Contacto</th><th>Turno</th><th>Contactos</th></tr>";
}
$indice=1;
while($dat=mysql_fetch_array($resp)) {
	$cod_contacto=$dat[0];
	$cod_ciclo=$dat[1];
	$dia_contacto=$dat[3];
	$turno=$dat[4];
	$zona_de_viaje=$dat[5];
	if($zona_de_viaje==1) {	
		$fondo_fila="#FFD8BF";
	} else {	
		$fondo_fila="";
	}
	$sql1="SELECT c.orden_visita, m.ap_pat_med, m.ap_mat_med, m.nom_med, d.direccion, c.cod_especialidad, c.categoria_med, c.estado, m.cod_med 
	from $nombreTabla2 c, medicos m, direcciones_medicos d where (c.cod_contacto=$cod_contacto) and 
	(c.cod_visitador=$global_visitador) and (c.cod_med=m.cod_med) and (m.cod_med=d.cod_med) and (c.cod_zona=d.numero_direccion) 
	order by c.orden_visita";
	$resp1=mysql_query($sql1);
	$contacto="<table class='textomini'>";
	$contacto=$contacto."<tr><th width='5%'>Orden</th><th>Nro.Cont.</th><th width='25%'>Medico</th><th width='5%'>Especialidad</th>
	<th width='10%'>Categoria</th><th width='30%'>Direccion</th><th width='15%'>Zona</th><th>Obs</th></tr>";
	while($dat1=mysql_fetch_array($resp1)) {
		$orden_visita  = $dat1[0];
		$pat           = $dat1[1];
		$mat           = $dat1[2];
		$nombre        = $dat1[3];
		$direccion     = $dat1[4];
		$espe          = $dat1[5];
		$cat           = $dat1[6];
		$cod_med       = $dat1[8];
		$zona_f        = $dat1[9];
		$nombre_medico = "$pat $mat $nombre";

		
		
		$sqlFrecEspecial = "SELECT md.cod_dia_agrupado, md.nro_visita from medico_frec_especial m, medico_frec_especialdetalle md where m.cod_ciclo = '$ciclo_global' and m.cod_gestion = '$codigo_gestion' and m.cod_med = '$cod_med' and m.cod_visitador='$global_visitador'";

		$respFrecEspecial = mysql_query($sqlFrecEspecial);
		$numFrecEspecial  = mysql_num_rows($respFrecEspecial);
		if($numFrecEspecial>0){
			$fondo_filaDet = "#aaaaff";
		} else{
			$fondo_filaDet = "";
		}

		//verificamos si el medico esta fuera de la linea y fuera de la asignacion del visitador
		$sqlVeriLinea="select count(*) from categorias_lineas c where 
			c.cod_med='$cod_med' and c.codigo_linea='$global_linea' and c.cod_especialidad='$espe' and c.categoria_med='$cat'";
		$respVeriLinea=mysql_query($sqlVeriLinea);
		$banderaVeriLinea=mysql_result($respVeriLinea,0,0);
		$txtVeriLinea="";
		if($banderaVeriLinea==0){
			$txtVeriLinea="<span style='color:red'>[Retirado/Linea]</span>";
		}
		
		$sqlVeriAsignacion="select count(*) from medico_asignado_visitador m where m.cod_med='$cod_med' and 
			m.codigo_visitador='$global_visitador' and m.codigo_linea='$global_linea'";
		$respVeriAsignacion=mysql_query($sqlVeriAsignacion);
		$banderaVeriAsignacion=mysql_result($respVeriAsignacion,0,0);
		$txtVeriAsignacion="";
		if($banderaVeriAsignacion==0){
			$txtVeriAsignacion="<span style='color:red'>[Retirado/Visitador]</span>";
		}

		$contacto=$contacto."<tr bgcolor='$fondo_filaDet'><td align='center'>$dat1[0]</td><td>$indice</td>
		<td>&nbsp;$nombre_medico</td><td>&nbsp;$espe</td><td align='center'>$cat</td><td>&nbsp;$direccion </td>
		<td align='center'>$zona_f</td><td>$txtVeriLinea $txtVeriAsignacion</td></tr>";
		$indice++;
	}
	$contacto=$contacto."</table>";
	if($aprobado!=1) {	
		echo "<tr bgcolor='$fondo_fila'><td align='left'><input type='checkbox' name='cod_contacto' value=$cod_contacto></td><td align='center'>$dia_contacto</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	} else {	
		echo "<tr bgcolor='$fondo_fila'><td align='left'>$dia_contacto</td><td align='center'>$turno</td><td align='center'>$contacto</td></tr>";
	}
}
echo "</table></center><br>";

echo"\n<table align='center'><tr><td><a href='navegador_rutero_maestro.php'><img  border='0'src='imagenes/back.png' width='40'></a></td></tr></table>";

if($aprobado!=1) {	
	echo "<div class='divBotones'>";
	if($filas_rutero==0) {	
		echo "<input type='button' value='Replicar Rutero' class='boton' onclick='recuperar_contactos()'>";
	}
	echo "&nbsp;<input type='button' value='Adicionar' name='adicionar' class='boton' onclick='enviar_nav()'>
			<input type='button' value='Editar' name='Editar' class='boton' onclick='editar_nav(this.form)'>
			<input type='button' value='Completar 1/2 ciclo' class='boton' onclick='completar_ciclo(this.form)'>
			<input type='button' value='Eliminar' name='eliminar' class='boton2' onclick='eliminar_nav(this.form)'>
		</div>";
}

echo "</form>";
?>