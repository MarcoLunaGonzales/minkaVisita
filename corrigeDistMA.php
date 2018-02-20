<?php
require("conexion.inc");

$sql="select a.espe_linea, a.linea_mkt, dist_a, dist_b, dist_c from 
	asignacion_ma_excel_detalle a where codigo_ma in (2359, 2807, 2757, 2927, 2968, 3015, 3018) group by linea_mkt, espe_linea";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$espe=$dat[0];
	$codLinea=$dat[1];
	$cantA=$dat[2];
	$cantB=$dat[3];
	$cantC=$dat[4];
	
	echo "$espe $codLinea $cantA $cantB $cantC <br>";
	
	$sqlRutero="select rd.cod_especialidad, rd.categoria_med, rc.cod_visitador, rd.cod_med, count(*) from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_gestion=1011 and rc.codigo_ciclo=4 
	and rc.codigo_linea=$codLinea and rd.cod_especialidad='$espe' group by cod_especialidad, categoria_med, cod_visitador, rd.cod_med order by categoria_med";
	$respRutero=mysql_query($sqlRutero);
	echo "<table border=1>";
	while($datRutero=mysql_fetch_array($respRutero)){
		$codEspe=$datRutero[0];
		$catMed=$datRutero[1];
		$codVisitador=$datRutero[2];
		$codMed=$datRutero[3];
		$cantidad=$datRutero[4];
		
		echo "<tr><td>$codEspe</td><td>$catMed</td><td>$codLinea</td><td>$codVisitador</td><td>$codMed</td><td>$cantidad</td></tr>";
	}
	echo "</table>";
}

?>