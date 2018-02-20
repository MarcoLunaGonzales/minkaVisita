<?php
require("conexion.inc");

$sql="select a.especialidad, a.ciudad, a.producto, a.linea_mkt, a.contacto, a.linea, count(*) from asignacion_productos_excel_detalle a 
group by a.especialidad, a.ciudad, a.producto, a.linea_mkt, a.contacto, a.linea HAVING 
count(*)>1 order by a.linea_mkt";

echo $sql;

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$espe=$dat[0];
	$ciudad=$dat[1];
	$producto=$dat[2];
	$lineaMkt=$dat[3];
	$contacto=$dat[4];
	$lineaVis=$dat[5];
	
	$sqlConsulta="select a.especialidad, a.ciudad, a.producto, a.linea_mkt, a.contacto, min(a.posicion) 
		from asignacion_productos_excel_detalle a where a.especialidad='$espe' and a.ciudad='$ciudad' and a.producto='$producto' 
		and a.contacto='$contacto' and a.linea_mkt='$lineaMkt' and linea='$lineaVis'";
	$respConsulta=mysql_query($sqlConsulta);
	while($datConsulta=mysql_fetch_array($respConsulta)){
		$codProducto=$datConsulta[2];
		$posicion=$datConsulta[5];
		echo "$espe $ciudad linea; $lineaMkt lineaVis: $lineaVis Contact: $contacto $codProducto $posicion <br>";
		
		$sqlDel="delete from asignacion_productos_excel_detalle where especialidad='$espe' 
		and ciudad='$ciudad' and producto='$codProducto' and contacto='$contacto' and linea_mkt='$lineaMkt' 
		and linea='$lineaVis' and posicion>$posicion";
		//echo $sqlDel."<br>";
		$respDel=mysql_query($sqlDel);
		
	}
}
?>