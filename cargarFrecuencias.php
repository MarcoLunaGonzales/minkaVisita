<?php
require("conexion.inc");
$rpt_territorio=113;

$sqlLineas="select l.`codigo_linea` from `lineas` l where l.`linea_promocion`=1";
$respLineas=mysql_query($sqlLineas);
while($datLineas=mysql_fetch_array($respLineas)){
	$codLinea=$datLineas[0];
	$sqlEspe="select m.`cod_med`, c.`categoria_med`, c.`cod_especialidad` from `categorias_lineas` c, medicos m 
			where c.`codigo_linea` = '$codLinea' and m.`cod_med` = c.`cod_med` and m.`cod_ciudad` = '$rpt_territorio'";
	$respEspe=mysql_query($sqlEspe);
	while($datEspe=mysql_fetch_array($respEspe)){
		$codMed=$datEspe[0];
		$catMed=$datEspe[1];
		$codEspe=$datEspe[2];
		echo "$codMed $codEspe $catMed <br>";
		$sqlGrilla="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd 
				where g.`codigo_grilla` = gd.`codigo_grilla` and g.`agencia`='$rpt_territorio' and 
				g.`estado`=1 and g.`codigo_linea`='$codLinea' and gd.`cod_especialidad`='$codEspe' and gd.`cod_categoria`='$catMed'";
		$respGrilla=mysql_query($sqlGrilla);
		$frecuenciaMedico=mysql_result($respGrilla,0,0);
		$sqlUpd="update `categorias_lineas` set frecuencia_linea=$frecuenciaMedico, frecuencia_permitida=$frecuenciaMedico
						where codigo_linea='$codLinea' and cod_med='$codMed' and cod_especialidad='$codEspe' and categoria_med='$catMed'";
		$respUpd=mysql_query($sqlUpd);
	}
}

?>