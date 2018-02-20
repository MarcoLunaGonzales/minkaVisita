<?php
require("conexion.inc");
require("estilos_reportes.inc");
require("imprimeRuteroMaestroConsolidado3.php");
set_time_limit(0);
$tipoRuteroRpt=$tipoRuteroRpt;
$gestionCicloRpt=$gestionCicloRpt;
$codigoLinea=$rpt_linea;
$codigos=explode("|",$gestionCicloRpt);
$codigoCiclo=$codigos[0];
$codigoGestion=$codigos[1];
$nombreGestion=$codigos[2];
$rpt_visitador=$rpt_visitador;

if($tipoRuteroRpt==0){
	$nombreTipoRutero="En Rutero Maestro";
}
if($tipoRuteroRpt==1){
	$nombreTipoRutero="En Rutero Maestro Aprobado";
}

$vectorVisitador=explode(",",$rpt_visitador);
$tamanoVector=sizeof($vectorVisitador);

echo "<center><table border='0' class='textotit'><tr><th>M&eacute;dicos en Rutero Maestro Resumido x Visitador
			<br>Gestion: $nombreGestion Ciclo: $codigoCiclo<br>Clasificaci&oacute;n: $nombreTipoRutero</th></tr></table></center><br>";

for($i=0;$i<=$tamanoVector-1;$i++){
	$codigoVisitador=$vectorVisitador[$i];
	imprimeRuteroMaestro($codigoGestion,$codigoCiclo,$tipoRuteroRpt,$codigoVisitador,$i);
	echo "<br>";
}
?>