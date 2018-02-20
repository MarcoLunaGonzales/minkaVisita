<?php
require("conexion.inc");
require("estilos_visitador.inc");
require("funcion_nombres.php");
$rutero=$rutero;
$codCiclo=$cod_ciclo;
$codigoGestion=$codigo_gestion;

echo "<table border='0' class='textotit' align='center'><tr><th>Detalle de Medicos con Frecuencia Reducida</th></tr></table>";
echo "<table border='1' width='50%' cellspacing='0' class='texto' align='center'><tr>
<th>Medico</th><th>Detalle</th>
<th>&nbsp;</th></tr>";

$sqlMedicos="select distinct(rd.`cod_med`)
	from `rutero_maestro_cab` rc, `rutero_maestro` r, `rutero_maestro_detalle` rd, medicos m
	where rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and rc.`cod_rutero` = $rutero 
	and rd.cod_med=m.cod_med order by m.ap_pat_med";

$respMedicos=mysql_query($sqlMedicos);
while($datMedicos=mysql_fetch_array($respMedicos)){
	$codMed=$datMedicos[0];
	$nombreMed=nombreMedico($codMed);
	
	$sqlDetalle="select md.`cod_dia`, md.`cod_dia_agrupado` from `medico_frec_especial` m, `medico_frec_especialdetalle` md 
	where m.`cod_med_frec` = md.`cod_med_frec` and m.`cod_ciclo` = $codCiclo and m.`cod_gestion` = $codigoGestion and m.`cod_med`=$codMed";
	$respDetalle=mysql_query($sqlDetalle);
	$cadDetalle="<table border=1 class='textomini' align='center'><tr><th>Dia Contacto</th><th>Dia Agrupacion</th></tr>";
	$bandera=0;
	while($datDetalle=mysql_fetch_array($respDetalle)){
		$codDia=$datDetalle[0];
		$nombreDia=nombreDia($codDia);
		$codDiaAgrupado=$datDetalle[1];
		$nombreDiaAgrupado=nombreDia($codDiaAgrupado);
		$cadDetalle.="<tr><td>$nombreDia</td><td>$nombreDiaAgrupado</td></tr>";
		$bandera=1;
	}
	$cadDetalle.="</table>";
	
	if($bandera==0){
		echo "<tr><td>$nombreMed</td><td>- -</td>
		<td><a href='registrarDetalleMedicosFrecEspecial.php?cod_rutero=$rutero&cod_med=$codMed&cod_ciclo=$codCiclo&cod_gestion=$codigoGestion'>Detallar Registro</a></td></tr>";	
	}else{
		echo "<tr><td>$nombreMed</td><td>$cadDetalle</td>
		<td><a href='registrarDetalleMedicosFrecEspecial.php?cod_rutero=$rutero&cod_med=$codMed&cod_ciclo=$codCiclo&cod_gestion=$codigoGestion'>Detallar Registro</a></td></tr>";	
	}
	
}

?>