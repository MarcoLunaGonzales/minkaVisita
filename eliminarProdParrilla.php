<?php
require("conexion.inc");

//$codProducto="CFMM0094";
$lineasMktR="1009, 1023";
$ciudades="115";
$especialidades="'OFT'";


$sql="select especialidad, posicion, ciudad, producto, linea_mkt, contacto, linea from asignacion_productos_excel_detalle a 
where a.linea_mkt in ($lineasMktR) and ciudad in ($ciudades) and especialidad in ($especialidades)
GROUP BY especialidad, ciudad, linea_mkt, contacto, linea";

echo $sql;

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$espe=$dat[0];
	$posicion=$dat[1];
	$ciudad=$dat[2];
	$lineaMkt=$dat[4];
	$contacto=$dat[5];
	$lineaVis=$dat[6];
	
	$sqlDel="delete from asignacion_productos_excel_detalle where especialidad='$espe' and ciudad='$ciudad' and linea_mkt='$lineaMkt' 
	and contacto='$contacto' and linea='$lineaVis'";
	$respDel=mysql_query($sqlDel);
	
}
?>