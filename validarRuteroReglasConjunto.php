<?php
require("funcion_nombres.php");
require("conexion.inc");

function validaRuteroReglasConjunto($codVisitador, $codCiclo, $codGestion, $codLinea){
$sqlNombreDias="select id, dia_contacto from orden_dias order by 1";
$respNombreDias=mysql_query($sqlNombreDias);
$vNombres[]=array();
$j=0;
while($datNombreDias=mysql_fetch_array($respNombreDias)){
	$vNombres[$j]=$datNombreDias[1];
	$j++;
}

$banderaProc=0;



$sqlMedicos="select distinct (rd.`cod_med`) from `rutero_maestro_cab` rc, `rutero_maestro` r, 
		`rutero_maestro_detalle` rd where rc.`cod_rutero` = r.`cod_rutero` and r.`cod_contacto` = rd.`cod_contacto` and 
		rc.`cod_visitador` = $codVisitador and rc.`codigo_ciclo` = $codCiclo and rc.`codigo_gestion` = $codGestion and rc.codigo_linea='$codLinea'";
		
$respMedicos=mysql_query($sqlMedicos);
echo "<table border=1 class='texto' align='center' cellspacing=0 cellpaddin=0>
<tr><th colspan='3'>Validacion de Secuencia en Rutero</th></tr>
<tr><th>Medico</th><th>Contactos</th><th>Dias de Contacto</th></tr>";
while($datMedicos=mysql_fetch_array($respMedicos)){
	$codMed=$datMedicos[0];
	$sqlDias="select o.id from `rutero_maestro_cab` rc, `rutero_maestro` r, 
		`rutero_maestro_detalle` rd, `orden_dias` o where rc.`cod_rutero` = r.`cod_rutero` and 
		r.`cod_contacto` = rd.`cod_contacto` and rc.`codigo_ciclo` = $codCiclo and 
		rc.`codigo_gestion` = $codGestion and o.`dia_contacto`=r.`dia_contacto` and  rd.`cod_med`=$codMed and rc.codigo_linea='$codLinea' 
		order by o.id";
	
	
	$respDias=mysql_query($sqlDias);

	$numContactos=mysql_num_rows($respDias);

	$vDias[]=array();	
	$i=0;
	while($datDias=mysql_fetch_array($respDias)){
		$codDia=$datDias[0];
		$vDias[$i]=$codDia;
		$i++;
	}
		
	for($j=0;$j<=$i-2;$j++){
		if(($vDias[$j+1]-$vDias[$j]) < 1){
				$nombreMedico=nombreMedico($codMed);
				
				echo "<tr><td>$codMed $nombreMedico</td><td>$vDias[$j]</td></tr>";
				
		}
	}
	
	unset($vDias);	
}
echo "</table>";
	return $banderaProc;
}
?>