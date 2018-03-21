<script language='Javascript'>
function editar_contactos(f, dia) {	
	location.href='editar_contactos_dia.php?dia_contacto='+dia+'&turno=Am';
}

function funcionEnvia(cad1, cad2, cadLink){
	var cadenaFinal=cadLink+cad2;
	window.open(cadenaFinal,'','scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
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
		alert('Debe seleccionar solamente un contacto para registrar su visita.');
	}
	else {
		if(j==0) {
			alert('Debe seleccionar un contacto para registrar su visita.');
		}
		else {
			//window.open('registrar_visita_detalle_parrilla.php?cod_contacto='+j_contacto+'&visita=1','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
			window.open('registrar_visita_detalle.php?cod_contacto='+j_contacto+'','','scrollbars=yes,status=yes,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800');
		}
	}
}
function reportar_novisita(f) {	
	var i;
	var j=0;
	var j_contacto;
	var motivo;
	for(i=0;i<=f.length-1;i++) {
		if(f.elements[i].type=='checkbox') {	
			if(f.elements[i].checked==true) {	
				j_contacto=f.elements[i].value;
				motivo=f.elements[i+1].value;
				j=j+1;
			}
		}
	}
	window.open('registrar_no_visita.php?cod_contacto='+j_contacto+'&dia_registro=$dia_registro', '', 'scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=300');
	//scrollbars=yes,status=no,toolbar=no,directories=no,menubar=no,resizable=yes,width=1000,height=800
}
function desactiva_checkbox(chk,f) {	
	var ii;
	if(chk.checked==true) {	
		for(ii=0;ii<=f.length-1;ii++) {	
			if(f.elements[ii].type=='checkbox') {	
				f.elements[ii].checked=false;
			}
		}
		chk.checked=true;
	}
	else {	
		for(ii=0;ii<=f.length-1;ii++) {	
			if(f.elements[ii].type=='checkbox') {	
				f.elements[ii].checked=false;
			}
		}
	}
}
</script>

<?php

require("estilos_visitador.inc");
require("conexion.inc");
$sql_dias_ini_fin="SELECT fecha_ini,fecha_fin from ciclos where cod_ciclo='$ciclo_global' and codigo_gestion='$codigo_gestion'";
$resp_dias_ini_fin=mysql_query($sql_dias_ini_fin);
$dat_dias=mysql_fetch_array($resp_dias_ini_fin);
$fecha_ini_actual=$dat_dias[0];
$fecha_fin_actual=$dat_dias[1];
$fecha_actual=$fecha_ini_actual;
$inicio=$fecha_ini_actual;
$k=0;
list($anio,$mes,$dia)=explode("-",$fecha_actual);
$dia1=$dia;
while($inicio<$fecha_fin_actual) {
	$ban=0;
	while($ban==0) {	
		$nueva1 = mktime(0,0,0, $mes,$dia1,$anio);
		$dia_semana=date("l",$nueva1);
		if($dia_semana=='Sunday' or $dia_semana=='Saturday') {	
			$dia1=$dia1+1;
		}
		else {	
			$ban=1;
		}
	}
	$num_dia=intval($k/5)+1;
	if($dia_semana=='Monday'){$dias[$k]="Lunes $num_dia";}
	if($dia_semana=='Tuesday'){$dias[$k]="Martes $num_dia";}
	if($dia_semana=='Wednesday'){$dias[$k]="Miercoles $num_dia";}
	if($dia_semana=='Thursday'){$dias[$k]="Jueves $num_dia";}
	if($dia_semana=='Friday'){$dias[$k]="Viernes $num_dia";}

	$fecha_actual=date("Y-m-d",$nueva1);
	$inicio=$fecha_actual;
	list($anio,$mes,$dia)=explode("-",$fecha_actual);
	$dia1=$dia+1;
	$fecha_actual_formato="$dia/$mes/$anio";
	$fechas[$k]=$fecha_actual_formato;
	$k++;
}
$contador=1;
$fecha_sistema=date("d/m/Y");
list($d_actual,$m_actual,$a_actual)=explode("/",$fecha_sistema);
$sec_actual=mktime(0,0,0,$m_actual,$d_actual,$a_actual);
for($i=0;$i<=$k-1;$i++) {	
	list($d_comp,$m_comp,$a_comp)=explode("/",$fechas[$i]);
	$sec_comp=mktime(0,0,0,$m_comp,$d_comp,$a_comp);
	if($sec_comp<=$sec_actual) {	
		$posicion=$i;
	}
}
echo "<form action='' method='post'>";
echo "<h1>Registro de Visita</h1>";

echo "<center><table class='texto'>";
echo "<tr>";
$ciclo_anterior=$ciclo_global-1;

if($posicion<=4) {
	for($j=5-$posicion;$j>=0;$j--) {
		$dia_rutero=$dias[$k-$j];
		$fecha_rutero=$fechas[$k-$j];
		echo "<th class='textomini'><a href='registrar_visita_medica.php?dia_registro=$dia_rutero&cod_ciclo=$ciclo_anterior&fecha_registro=$fecha_rutero'>$dia_rutero</a></th>";
	}
}
for($j=40;$j>=0;$j--) {
	$dia_rutero=$dias[$posicion-$j];
	$fecha_rutero=$fechas[$posicion-$j];
	if($posicion-$j>=0) {
		echo "<th class='textomini'>&nbsp;<a href='registrar_visita_medica.php?dia_registro=$dia_rutero&cod_ciclo=$ciclo_global&fecha_registro=$fecha_rutero'>$dia_rutero</a></th>";
	}
}
echo "</tr>";

$dia_registro=$_GET['dia_registro'];
$fecha_registro=$_GET['fecha_registro'];

echo "<tr><th colspan='28'><br>Fecha de Registro: $dia_registro</th></tr></table></center>|<br>";


echo "<center><table class='texto'>";
echo "<tr><th>&nbsp;</th>
<th>Turno</th><th>Orden Visita</th>
<th>M&eacute;dico</th><th>Especialidad</th>
<th>Categoria</th><th>Num. Visita</th><th>Estado</th><th>Linea</th>
<th>Registrar<br>Visita</th><th>Registrar<br>Baja</th>
</tr>";

$sql="SELECT c.turno, m.ap_pat_med, m.ap_mat_med, m.nom_med, cd.categoria_med, cd.cod_especialidad, cd.orden_visita, c.cod_contacto, 
cd.estado, m.cod_med, c.codigo_linea, (select nombre_linea from lineas where codigo_linea=c.codigo_linea)linea 
from rutero c, rutero_detalle cd, medicos m where c.codigo_gestion='$codigo_gestion' and 
c.cod_ciclo='$cod_ciclo' and c.dia_contacto='$dia_registro' and 
c.cod_visitador=$global_visitador and m.cod_med=cd.cod_med and c.cod_visitador=cd.cod_visitador and 
c.cod_contacto=cd.cod_contacto order by linea, c.turno,cd.orden_visita";

//echo $sql;
$resp=mysql_query($sql);
$bandera=0;
$indice=1;
while($dat=mysql_fetch_array($resp)) {   
	$turno=$dat[0];
	$ap_pat=$dat[1];
	$ap_mat=$dat[2];
	$nom=$dat[3];
	$categoria=$dat[4];
	$especialidad=$dat[5];
	$orden_visita=$dat[6];
	$cod_contacto=$dat[7];
	$estado=$dat[8];
	$cod_med=$dat[9];
	$codLineaMkt=$dat[10];
	$nombreLineaMkt=$dat[11];
	
	
	$sqlNumVis="SELECT r.dia_contacto from rutero r, rutero_detalle rd, 
	orden_dias o where r.cod_contacto=rd.cod_contacto and 
	r.cod_visitador='$global_visitador' and r.cod_ciclo='$ciclo_global' and r.codigo_gestion='$codigo_gestion' and 
	r.dia_contacto=o.dia_contacto and rd.cod_med='$cod_med' and r.codigo_linea='$codLineaMkt' order by id";	
	
	//echo $sqlNumVis;
	
	$respNumVis=mysql_query($sqlNumVis);
	$indice=1;
	while($datNumVis=mysql_fetch_array($respNumVis)){
		$diaContactoX=$datNumVis[0];
		if($diaContactoX==$dia_registro){
			$numVisita=$indice;
		}
		$indice++;
	}
	
	$nombre_medico="$ap_pat $ap_mat $nom";
	$valor="$cod_contacto-$orden_visita-$global_visitador-$fecha_registro-$global_agencia-$numVisita-$codLineaMkt";
	$sql_baja_dias="SELECT bd.codigo_linea from baja_dias b, baja_dias_detalle bd, baja_dias_detalle_visitador 
	bv where b.codigo_baja=bd.codigo_baja and bd.codigo_linea='$global_linea' and b.ciclo='$ciclo_global' 
	and b.gestion='$codigo_gestion'and	b.codigo_ciudad='$global_agencia' and b.dia_contacto='$dia_registro' and b.turno='$turno'and bd.codigo_baja=bv.codigo_baja and bd.codigo_linea=bv.codigo_linea and bv.codigo_visitador='$global_visitador';"; 
	$resp_baja_dias=mysql_query($sql_baja_dias);
	$filas_baja_dias=mysql_num_rows($resp_baja_dias);
	for($l=0;$l<=20;$l++) {	
		if($dias[$l]==$dia_registro) {	
			$fecha_registro=$fechas[$l];
			$idDiaContacto=$l+1;
		}
	}

	
	
	$fecha_registro_real=$fecha_registro[6].$fecha_registro[7].$fecha_registro[8].$fecha_registro[9]."-".$fecha_registro[3].$fecha_registro[4]."-".$fecha_registro[0].$fecha_registro[1];
	$sql_baja_medico=mysql_query("SELECT cod_med from baja_medicos where cod_med='$cod_med' and inicio<='$fecha_registro_real' and fin>='$fecha_registro_real' and codigo_linea='$global_linea'");
	$filas_baja_medico=mysql_num_rows($sql_baja_medico);
	if($estado==0) {	
		$val_estado="<img src='imagenes/pendiente.png' width='30'>";
		$checkbox="<input type='checkbox' value='$valor' name='cod_contacto$indice' onclick='desactiva_checkbox(this,this.form)'>";
	}
	if($estado==1) {	
		$val_estado="<img src='imagenes/si.png' width='30'>";
		$checkbox="&nbsp";
		$bandera=1;
		$cod_contacto_registro=$cod_contacto;
	}
	if($estado==2) {	
		$val_estado="<img src='imagenes/no.png' width='30'>";
		$checkbox="&nbsp";
		$bandera=1;
		$cod_contacto_registro=$cod_contacto;
	}
	if($estado==3) {	
		$val_estado="<img src='imagenes/btn_modificar.png' width='30'>";
		$checkbox="&nbsp";
		$bandera=1;
		$cod_contacto_registro=$cod_contacto;
	}
	if($estado==6) {	
		$val_estado="<img src='imagenes/pulgarabajo.png' width='30'>";
		$checkbox="<input type='checkbox' value='$valor' name='cod_contacto$indice' onclick='desactiva_checkbox(this,this.form)'>";
	}
	if($filas_baja_dias!=0) {	
		$checkbox="&nbsp";
		$fondo_fila="#66CCFF";
	}
	else {	
		$fondo_fila="";
	}
	
	if($filas_baja_medico!=0) {	
		$checkbox="&nbsp";
		$fondo_fila="#AAAAAA";
	}
	
	$sqlFrecEspecial="SELECT md.cod_med_frec, md.cod_dia_agrupado, md.nro_visita from medico_frec_especial m, 
	medico_frec_especialdetalle md where m.cod_med_frec=md.cod_med_frec and  m.cod_ciclo = '$ciclo_global' and m.cod_gestion = '$codigo_gestion' 
	and m.cod_med = '$cod_med' and m.cod_visitador='$global_visitador' and md.cod_dia_agrupado=$idDiaContacto order by md.cod_dia_agrupado";
	
	//echo $sqlFrecEspecial;
	$respFrecEspecial=mysql_query($sqlFrecEspecial);
	$numFrecEspecial=mysql_num_rows($respFrecEspecial);
	if($numFrecEspecial>0){
		$fondo_fila="#aaaaff";
	}
	$cadFrecEspecial="";
	while($datFrecEspecial=mysql_fetch_array($respFrecEspecial)){
		$codMedFrec=$datFrecEspecial[0];
		$cadFrecEspecial.=$datFrecEspecial[2]."-";
	}
	$numCaracteres=strlen($cadFrecEspecial);
	$cadFrecEspecial=substr($cadFrecEspecial,0,$numCaracteres-1);

	
	
	$sqlLineaVis="SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_visitadores lv, lineas_visita_especialidad le where l.codigo_l_visita=lv.codigo_l_visita and l.codigo_l_visita=le.codigo_l_visita and lv.codigo_l_visita=le.codigo_l_visita and l.codigo_linea='$global_linea'and lv.codigo_funcionario='$global_visitador' and le.cod_especialidad='$especialidad'";
	$respLineaVis=mysql_query($sqlLineaVis);
	$numFilasLineaVis=mysql_num_rows($respLineaVis);
	if($numFilasLineaVis>0){
		$codLineaVisita=mysql_result($respLineaVis,0,0);
	}else{
		$codLineaVisita=0;
	}

	echo "<tr bgcolor='$fondo_fila'>
	<td align='center'>&nbsp;</td>
	<td align='center'>$turno</td>
	<td align='center'>$orden_visita</td>
	<td>$nombre_medico</td>
	<td align='center'>$especialidad </td>
	<td align='center'>$categoria </td>
	<td align='center'>$numVisita</td>
	<td align='center'>$val_estado</td>";
	$sql_categorizacion = mysql_query("SELECT cod_med from categorizacion_medico where cod_med = $cod_med");
	$num_sql_categorizacion  = mysql_num_rows($sql_categorizacion); 
	if($num_sql_categorizacion == 0){
		$cadCategorizacion = "<a href='formulario_medico.php?cod_medico=$cod_med' >Categorizacion Medico</a>";
	} else{
		$cadCategorizacion = "Categorizacion medico ya llenado";
	}
	echo "<td>$nombreLineaMkt</td>";
	
	if($estado==0){
		echo "<td align='center'><a href='registrar_visita_detalle.php?cod_contacto=$valor'><img src='imagenes/go.png' width='40' title='Registrar Visita'></a></td>";
		echo "<td align='center'><a href='registrar_no_visita.php?cod_contacto=$valor&dia_registro=$dia_registro'><img src='imagenes/enter.png' width='40' title='Registrar Baja'></a></td>";
	}else{
		echo "<td align='center'>-</td>";
		echo "<td align='center'>-</td>";
	}
	
	if($numFrecEspecial==0){
		echo "</tr>";
	}else{
		$cadLink="registrarVisitaParrilla.php?codMed=$cod_med&codMedFrec=$codMedFrec$&codEspe=$especialidad&codCat=$categoria&codContacto=$cod_contacto&nombreMed=$nombre_medico&codLineaVisita=$codLineaVisita&numVisita=";
	}
	
	$indice++;
}
echo "</table></center><br>";
$sql_fecha_registro="SELECT min(fecha_registro) from registro_visita where cod_contacto='$cod_contacto'";
$resp_fecha_registro=mysql_query($sql_fecha_registro);
$dat_fecha_registro=mysql_fetch_array($resp_fecha_registro);
$fecha_en_que_se_registro=$dat_fecha_registro[0];
list($anio,$mes,$dia)=explode("-",$fecha_en_que_se_registro);
$dia1=$dia+1;

/*echo "<div class='divBotones'>
	<!--input type='button' value='Editar Contactos' class='boton' onclick='editar_contactos(this.form,  \"$dia_registro\")'-->
	<input type='button' value='Registrar Visita' class='boton' onclick='editar_nav(this.form)'>
	<input type='button' value='Registrar No Visita' class='boton' onclick='reportar_novisita(this.form)'>
	</div>";*/


echo "<center><table border='0' class='texto'>
<tr><th>Descripcion</th><th>Icono</th><th>Descripcion</th><th>Icono</th><th>Descripcion</th><th>Icono</th><th>Descripcion</th><th>Icono</th></tr>
<tr>
<td>D&iacute;as de baja</td><td bgcolor='#66CCFF'></td>
<td>Medicos con baja</td><td bgcolor='#AAAAAA'></td>
<td>Medicos Frecuencia Especial</td><td bgcolor='#AAAAFF'></td>
<td>Visitado</td><td><img src='imagenes/si.png' width='30'></td>
</tr>
<tr>
<td>No Visitado</td><td><img src='imagenes/no.png' width='30'></td>
<td>Pendiente</td><td><img src='imagenes/pendiente.png' width='30'></td>
<td>Baja Pendiente Aprobacion</td><td><img src='imagenes/btn_modificar.png' width='30'></td>
<td>Baja No Aprobada</td><td><img src='imagenes/pulgarabajo.png' width='30'></td>
</tr>
</table></center><br>";


echo "</form>";
?>