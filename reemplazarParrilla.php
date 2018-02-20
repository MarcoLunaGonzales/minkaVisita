<?php
require("conexion.inc");

//$codProducto="CFMM0094";
$lineasMktR="1009, 1022, 1023";
$ciudades="115";
$especialidades="'OFT'";


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
	
	$sqlDel="delete from asignacion_productos_excel_detalle where especialidad='$espe' and ciudad='$ciudad' and linea_mkt='$lineaMkt' 
	and contacto='$contacto' and linea='$lineaVis'";
	$respDel=mysql_query($sqlDel);
	
	//INSERTAMOS EL NUEVO
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','1','$ciudad','M121','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','2','$ciudad','M89','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','3','$ciudad','M60','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','4','$ciudad','CFMM0094','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','5','$ciudad','CFCO0108','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','6','$ciudad','CFCO0196','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','7','$ciudad','CFMM0053','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','8','$ciudad','CFCO0126','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','9','$ciudad','M97','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	$sqlInsert="insert into asignacion_productos_excel_detalle(id, especialidad, linea, posicion, ciudad, producto, linea_mkt, contacto) 
		values(1, '$espe','$lineaVis','10','$ciudad','CFCO0121','$lineaMkt','$contacto')";
	$respUpd=mysql_query($sqlInsert);
	
}
?>