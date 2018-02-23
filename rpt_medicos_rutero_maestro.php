<script language='JavaScript'>
function totales(){
	var main=document.getElementById('main');   
	var numFilas=main.rows.length;
	var numCols=main.rows[0].cells.length;

	for(var j=1; j<=numCols-1; j++){
		var subtotal=0;
		for(var i=1; i<=numFilas-2; i++){
			var dato=parseInt(main.rows[i].cells[j].innerHTML);
			subtotal=subtotal+dato;
		}
		var fila=document.createElement('TH');
		main.rows[numFilas-1].appendChild(fila);
		main.rows[numFilas-1].cells[j].innerHTML=subtotal;
	}	 
}
</script>
<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("funcion_nombres.php");

require('imprimeRuteroMaestroEspecialidad.php');

$tipoRuteroRpt      = $tipoRuteroRpt;
$gestionCicloRpt    = $gestionCicloRpt;
$codigoLinea        = $rpt_linea;
$nombreLinea		= nombreLinea($codigoLinea);

$codigos            = explode("|",$gestionCicloRpt);
$codigoCiclo        = $codigos[0];
$codigoGestion      = $codigos[1];
$nombreGestion      = $codigos[2];
$codigoEspe         = $rpt_especialidad;
$codigoEspe1        = str_replace("`","'",$codigoEspe);
$vectorVisitador    = explode(",",$rpt_visitador);
$tamVectorVisitador = sizeof($vectorVisitador);

echo "<center><table border='0' class='textotit'><tr><th>Medicos en Rutero Maestro
<br> Gestion: $nombreGestion Ciclo: $codigoCiclo Linea: $nombreLinea</th></tr></table></center><br>";

for($i=0;$i<=$tamVectorVisitador-1;$i++) {
	$rpt_visitador    = $vectorVisitador[$i];
	$sql_visitador    = "SELECT paterno, materno, nombres, cod_ciudad from funcionarios where codigo_funcionario='$rpt_visitador'";
	$resp_visitador   = mysql_query($sql_visitador);
	$dat_visitador    = mysql_fetch_array($resp_visitador);
	$nombre_visitador = "$dat_visitador[0] $dat_visitador[1] $dat_visitador[2]";
	$codTerritorio    = $dat_visitador[3];
	
	if($tipoRuteroRpt==0){
		$sql="SELECT cod_rutero from rutero_maestro_cab where cod_visitador='$rpt_visitador' and codigo_linea='$codigoLinea' and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
		$resp=mysql_query($sql);
		$rutero_maestro=mysql_result($resp,0,0);
		$tabla1="rutero_maestro_cab";
		$tabla2="rutero_maestro";
		$tabla3="rutero_maestro_detalle";		
	}
	if($tipoRuteroRpt==1){
		$sql="SELECT cod_rutero from rutero_maestro_cab_aprobado where cod_visitador='$rpt_visitador' and codigo_linea='$codigoLinea' and codigo_ciclo='$codigoCiclo' and codigo_gestion='$codigoGestion'";
		$resp=mysql_query($sql);
		$rutero_maestro=mysql_result($resp,0,0);
		$tabla1="rutero_maestro_cab_aprobado";
		$tabla2="rutero_maestro_aprobado";
		$tabla3="rutero_maestro_detalle_aprobado";		
	}
	
	$sql_medicos="SELECT DISTINCT (rmd.cod_med), m.ap_pat_med, m.ap_mat_med, m.nom_med, rmd.categoria_med, rmd.cod_especialidad from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_rutero='$rutero_maestro' and rmc.cod_visitador='$rpt_visitador' and rmd.cod_visitador='$rpt_visitador'and rmd.cod_especialidad in ($codigoEspe1) order by rmd.cod_especialidad, rmd.categoria_med, m.ap_pat_med";
	
//	echo $sql_medicos;
	
	$resp_medicos=mysql_query($sql_medicos);
	echo "<center><table border='1' class='textomini' cellspacing='0' width='60%'>";
	echo "<tr><th>&nbsp;</th><th>Codigo</th><th>Visitador</th><th>Nombre</th><th>Especialidad</th><th>Categoria</th>
	<th>Contactos</th><th>Contactos Grilla</th><th>Diferencia</th></tr>";
	$indice_tabla=1;
	while($dat_medicos=mysql_fetch_array($resp_medicos)) {	
		$codigo_medico=$dat_medicos[0];
		$nombre_medico="$dat_medicos[1] $dat_medicos[2] $dat_medicos[3]";
		$categoria_med=$dat_medicos[4];
		$especialidad_med=$dat_medicos[5];

		$sql_cant_contactos = "SELECT rmd.cod_med from $tabla1 rmc, $tabla2 rm, $tabla3 rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.cod_visitador=rm.cod_visitador and rmc.cod_rutero='$rutero_maestro' and rmc.codigo_linea='$rpt_linea' and rmc.cod_visitador='$rpt_visitador'and rmd.cod_especialidad in ('$especialidad_med') and rmd.cod_med='$codigo_medico'";
		$resp_cant_contactos = mysql_query($sql_cant_contactos);
		$num_contactos = mysql_num_rows($resp_cant_contactos);

		$sqlGrilla = "SELECT gd.frecuencia from grilla g, grilla_detalle gd where g.codigo_grilla=gd.codigo_grilla and gd.cod_especialidad='$especialidad_med' and gd.cod_categoria='$categoria_med' and g.agencia=$codTerritorio and g.codigo_linea='$codigoLinea'";
		$respGrilla       = mysql_query($sqlGrilla);
		$frecuenciaGrilla = mysql_result($respGrilla,0,0);

		$diferencia = $num_contactos - $frecuenciaGrilla;
		if($diferencia == 0){
			$color = '#ffffff';
		}
		if($diferencia < 0){
			$color = '##00FF00';
		}
		if($diferencia > 0){
			$color = '##ff0000';
		}

		echo "<tr><td>$indice_tabla</td><td align='center'>$codigo_medico</td>
		<td>$nombre_visitador</td><td>$nombre_medico</td><td>$especialidad_med</td><td align='center'>$categoria_med</td>
		<td align='center'>$num_contactos</td><td align='center'>$frecuenciaGrilla</td>
		<td align='center' bgcolor='$color'>$diferencia</td></tr>";
		$indice_tabla++;
	}
	echo "</table></center>";

	imprimeRuteroMaestroEspe($codigoGestion,$codigoCiclo,$tipoRuteroRpt,$codigoLinea,$rpt_visitador,$rpt_visitador,$codigoEspe);
	echo "<br><br>";

}