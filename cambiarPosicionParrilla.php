<?php
require("conexion.inc");

$codProducto="M99";
/*$sqlUpd="update asignacion_productos_excel_detalle set posicion=1 where linea_mkt=1045 and producto='$codProducto'";
$respUpd=mysql_query($sqlUpd);*/

$sql="select especialidad, posicion, ciudad, producto, linea_mkt, contacto from asignacion_productos_excel_detalle a 
where a.linea_mkt=1045 and a.producto='m99' and posicion<>1
GROUP BY especialidad, ciudad, linea_mkt, contacto";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$espe=$dat[0];
	$posicion=$dat[1];
	$ciudad=$dat[2];
	$lineaMkt=$dat[4];
	$contacto=$dat[5];
	
	$sqlUpd="update asignacion_productos_excel_detalle set posicion=1 where especialidad='$espe' and 
		ciudad='$ciudad' and linea_mkt='$lineaMkt' and contacto='$contacto' and producto='$codProducto'";
	$respUpd=mysql_query($sqlUpd);
	
	$sqlUpd="update asignacion_productos_excel_detalle set posicion=posicion+1 where especialidad='$espe' and 
		ciudad='$ciudad' and linea_mkt='$lineaMkt' and contacto='$contacto' and producto not in ('$codProducto') and posicion<$posicion";
	$respUpd=mysql_query($sqlUpd);
	
	
	
}
?>