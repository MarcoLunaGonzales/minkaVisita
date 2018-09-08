<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript">
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

$sqlRegion="select c.idCiudadCRM from ciudades c where c.cod_ciudad='$global_agencia'";
$respRegion=mysql_query($sqlRegion);
$regionCUP=mysql_result($respRegion,0,0);

if($global_agencia==115){
	$sql="select m.cod_cup, m.region, m.nombre_medico, m.direccion, m.especialidad, m.regiontxt from cup_medicos m 
		order by 3";
}else{
	$sql="select m.cod_cup, m.region, m.nombre_medico, m.direccion, m.especialidad, m.regiontxt from cup_medicos m 
	where m.region='$regionCUP' order by 3";
}
	
$resp=mysql_query($sql);
echo "<form>";
echo "<h1>Medicos de CloseUP</h1>";
$indice_tabla=1;

echo "Buscar: <input type='text' size='30' id='kwd_search' value='' placeholder='Introduzca un criterio de busqueda'>";

echo "<center><table class='texto' id='my-table'>";
echo "<thead>
<tr><th>&nbsp;</th><th>Codigo</th><th>Region</th><th>Nombre</th><th>Direccion</th><th>Especialidad</th><th>CUPxLab</th><th>CUPxMercado</th></tr>
</thead>";

echo "<tbody>";
while($dat=mysql_fetch_array($resp)) {

	$codCUP=$dat[0];
	$codMed=0;
	$nombre_completo=$dat[2];
	$direccion=$dat[3];
	$especialidad=$dat[4];
	$regionTxt=$dat[5];
	
	$imgCUP="reportcup7.jpg";
	
	$cadReportes="<td align='center'><a href='verificaCUP.php?codCUP=$codCUP&codMed=$cod&parametro=1' target='_blank'>
	<img src='imagenes/$imgCUP' width='50px' title='Reporte por Laboratorio'></a></td>
	<td align='center'><a href='verificaCUP.php?codCUP=$codCUP&codMed=$cod&parametro=2' target='_blank'>
	<img src='imagenes/$imgCUP' width='50px' title='Reporte por Mercado'></a></td>";
	

	echo "<tr><td align='center'>$indice_tabla</td><td align='center'>$codCUP</td>
	<td>$regionTxt</td><td>&nbsp;$nombre_completo</td><td align='center'>&nbsp;$direccion</td>
	<td>$especialidad</td>$cadReportes
	</tr>";
	$indice_tabla++;
}
echo "</tbody></table>"
?>