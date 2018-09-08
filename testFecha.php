<?php

require("conexion.inc");

	$sqlMax="select max(d.fecha) from cup_datos d";
	$respMax=mysql_query($sqlMax);
	$fechaMaxima=mysql_result($respMax,0,0);
	
	$trim4Fin=date("M-y", strtotime($fechaMaxima));
	$trim4Ini=date("M-y", strtotime($fechaMaxima."- 2 month"));
	$trim4=$trim4Ini." ".$trim4Fin;
	echo $trim4."<br>";
	
	$trim3Fin=date("M-y", strtotime($fechaMaxima."- 3 month"));
	$trim3Ini=date("M-y", strtotime($fechaMaxima."- 5 month"));
	$trim3=$trim3Ini." ".$trim3Fin;
	echo $trim3."<br>";
	
	$trim2Fin=date("M-y", strtotime($fechaMaxima."- 6 month"));
	$trim2Ini=date("M-y", strtotime($fechaMaxima."- 8 month"));
	$trim2=$trim2Ini." ".$trim2Fin;
	echo $trim2."<br>";
	
	$trim1Fin=date("M-y", strtotime($fechaMaxima."- 9 month"));
	$trim1Ini=date("M-y", strtotime($fechaMaxima."- 11 month"));
	$trim1=$trim1Ini." ".$trim1Fin;
	echo $trim1."<br>";
	
	

?>