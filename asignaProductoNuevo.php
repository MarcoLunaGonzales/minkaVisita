<?php
require("conexion.inc");

$codProducto="M118";
/*$sqlUpd="update asignacion_productos_excel_detalle set posicion=1 where linea_mkt=1045 and producto='$codProducto'";
$respUpd=mysql_query($sqlUpd);*/
$lineasMktR="1009,1021,1022,1023";
$ciudades="115";
$especialidades="'REU','TRAUM'";

$sql="select especialidad, posicion, ciudad, producto, linea_mkt, contacto, linea from asignacion_productos_excel_detalle a 
where a.linea_mkt in ($lineasMktR) and ciudad not in ($ciudades) and especialidad in ($especialidades)
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
	
	
	$sqlUpd="update asignacion_productos_excel_detalle set posicion=posicion+1 where especialidad='$espe' and 
		ciudad='$ciudad' and linea_mkt='$lineaMkt' and contacto='$contacto' and producto not in ('$codProducto') and linea='$lineaVis' ";
	echo $sqlUpd."<br>";
	
	$respUpd=mysql_query($sqlUpd);
	
	//INSERTAMOS EL NUEVO
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','1','$ciudad','$codProducto','$lineaMkt','$contacto')";
	echo $sqlInsert."<br>";
	$respUpd=mysql_query($sqlInsert);
	
	
}
?>