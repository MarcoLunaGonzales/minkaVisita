<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
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
function cambiaRelacionCUP(f, id, codMed, codRegion, codCUP){
	var contenedor;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxRelacionaCUP.php?codMed='+codMed+'&codRegion='+codRegion+'&codCUP='+codCUP+'&id='+id,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}
function guardarRelacionCUP(combo, codMed, id){
	var contenedor;
	var codCUPX=combo.value;
	contenedor = document.getElementById(id);
	ajax=nuevoAjax();
	ajax.open('GET', 'ajaxGuardaRelacionCUP.php?codMed='+codMed+'&codCUP='+codCUPX,true);
	ajax.onreadystatechange=function() {
		if (ajax.readyState==4) {
			contenedor.innerHTML = ajax.responseText
		}
	}
	ajax.send(null)
}


$(document).ready(function(){
	$("#kwd_search").keyup(function(){
		if( $(this).val() != "")
		{
			$("#my-table tbody>tr").hide();
			$("#my-table td:contains-ci('" + $(this).val() + "')").parent("tr").show();
		}
		else
		{
			$("#my-table tbody>tr").show();
		}
	});
});
$.extend($.expr[":"], 
{
    "contains-ci": function(elem, i, match, array) 
	{
		return (elem.textContent || elem.innerText || $(elem).text() || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
	}
});

</script>
<?php

require("conexion.inc");
require("estilos_regional_pri.inc");

$sql_cab="SELECT f.paterno, f.materno, f.nombres from funcionarios f where f.codigo_funcionario = '$global_visitador'";
$resp_cab=mysql_query($sql_cab);
$dat_cab=mysql_fetch_array($resp_cab);
$nombre_funcionario="$dat_cab[0] $dat_cab[1] $dat_cab[2]";

$sql="SELECT distinct m.cod_med, m.ap_pat_med, m.ap_mat_med, m.nom_med,
	(select estado from estado_medico_registro where id=m.estado_registro)as estado,
	m.estado_registro, c.cod_especialidad, c.categoria_med, m.cod_closeup
	from medicos m, 
	categorias_lineas c,medico_asignado_visitador v 
	where m.cod_ciudad = '$global_agencia' and m.cod_med = c.cod_med and m.cod_med = v.cod_med 
	and v.codigo_visitador = '$global_visitador' order by m.ap_pat_med";
	
$resp=mysql_query($sql);
echo "<form>";
echo "<h1>Medicos Asignados e Informacion CUP<br>Visitador: $nombre_funcionario</h1>";

$sqlRegion="select c.idCiudadCRM from ciudades c where c.cod_ciudad='$global_agencia'";
$respRegion=mysql_query($sqlRegion);
$regionCUP=mysql_result($respRegion,0,0);

$indice_tabla=1;
echo "Buscar: <input type='text' size='30' id='kwd_search' value='' placeholder='Introduzca un criterio de busqueda'>";
echo "<center><table class='texto' id='my-table'>";
echo "<thead>
<tr><th>&nbsp;</th><th>Codigo</th><th>Nombre</th><th>Especialidades</th><th>CodCUP</th><th>Estado</th><th>CUPxLab</th><th>CUPxMercado</th></tr>
</thead>";

echo "<tbody>";
while($dat=mysql_fetch_array($resp)) {

	$cod=$dat[0];
	$pat=$dat[1];
	$mat=$dat[2];
	$nom=$dat[3];
	$nombre_completo="$pat $mat $nom";
	$nombreEstado=$dat[4];
	$codEstado=$dat[5];
	$codEspecialidad=$dat[6];
	$categoriaMed=$dat[7];
	$codCUP=$dat[8];
	$codCUPOriginal=$codCUP;
	
	if($codCUP==0){
		$codCUP="";
		$cadReportes="<td>&nbsp;</td>";
	}else{
		$sqlX="select cod_cup from cup_medicos where cod_cup='$codCUP'";
		$respX=mysql_query($sqlX);
		$numeroFilasX=mysql_num_rows($respX);
		if($numeroFilasX==0){
			$cadReportes="<td>&nbsp;</td><td>&nbsp;</td>";
			$codCUP=$codCUP."(Codigo No Existe)";
		}else{
			$cadReportes="<td align='center'><a href='verificaCUP.php?codCUP=$codCUP&codMed=$cod&parametro=1' target='_blank'>
			<img src='imagenes/reportcup1.png' width='40px' title='Reporte por Laboratorio'></a></td>
			<td align='center'><a href='verificaCUP.php?codCUP=$codCUP&codMed=$cod&parametro=2' target='_blank'>
			<img src='imagenes/reportcup1.png' width='40px' title='Reporte por Mercado'></a></td>";
		}
	}
	
	$especialidad="$codEspecialidad&nbsp;$categoriaMed";
	
	if($codEstado==1){
		$nombreEstado="<span style='color:green'>$nombreEstado</span>";
	}else{
		$nombreEstado="<span style='color:red'>$nombreEstado</span>";
	}
	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$cod</td>
	<td>&nbsp;$nombre_completo</td><td align='center'>&nbsp;$especialidad</td>
	<td>$codCUP 
	<div id='divCUP$indice_tabla'>
	</div>
	<a href='javascript:cambiaRelacionCUP(this.form, \"divCUP$indice_tabla\", \"$cod\",\"$regionCUP\",\"$codCUPOriginal\")'>
	<img src='imagenes/change.png' width='20'/></a>
	</td>
	<td>$nombreEstado</td>$cadReportes
	</tr>";
	$indice_tabla++;
}
echo "</tbody></table>"
?>