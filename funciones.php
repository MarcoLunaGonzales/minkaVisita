<?php

function redondear2($valor) { 
   $float_redondeado=round($valor * 100) / 100; 
   return $float_redondeado; 
}

function codigoParrilla(){
	$sql = "SELECT max(p.codigo_parrilla)+1 from parrilla p";
	$resp = mysql_query($sql);
	$num_filas = mysql_result($resp,0,0);
	return $num_filas;	
}

?>