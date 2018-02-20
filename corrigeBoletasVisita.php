<?php
require("conexion.inc");
require("funcionScaner.php");

$sql="select medico, id_boleta from boletas_visita_cabXXX";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$medico=$dat[0];
	$id=$dat[1];
	$medicoX=sanear_string($medico);
	
	$medicoX=preg_replace('/\s+/', ' ', $medicoX);
	
	$sqlUpd="update boletas_visita_cabXXX set medico='$medicoX' where id_boleta='$id'";
	$respUpd=mysql_query($sqlUpd);
		
		
	echo "medico  $medico <br>";
}



?>