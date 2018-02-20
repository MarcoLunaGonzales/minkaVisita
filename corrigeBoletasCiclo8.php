<?php
require("conexion.inc");

$sql="select b.longitud, b.latitud, b.firma, b.observacion, b.fecha_visita, id_medico, cod_contacto 
from boletas_visita_cabXXXRevisar b 
where b.estado=1";
$resp=mysql_query($sql);

$indice=1;
while($dat=mysql_fetch_array($resp)){
	$longitud=$dat[0];
	$latitud=$dat[1];
	$firma=$dat[2];
	$observacion=$dat[3];
	$fecha_visita=$dat[4];
	$id_medico=$dat[5];
	$cod_contacto=$dat[6];
	echo "$longitud $latitud $firma $observacion $fecha_visita $indice<br><br><br>";
	$indice++;
	
	$upd="update boletas_visita_cabXXX set estado=1, 
	longitud='$longitud', latitud='$latitud', firma='$firma', observacion='$observacion', 
	fecha_visita='$fecha_visita' where id_medico='$id_medico' and cod_contacto='$cod_contacto'";
	$respUpd=mysql_query($upd);
}

?>