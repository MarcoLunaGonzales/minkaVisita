<?php
require("conexion.inc");

$sql="select a.especialidad, a.ciudad, a.linea_mkt, a.contacto, a.linea from asignacion_productos_excel_detalle a 
group by a.especialidad, a.ciudad, a.linea_mkt, a.contacto, a.linea";

echo $sql;

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$espe=$dat[0];
	$ciudad=$dat[1];
	$lineaMkt=$dat[2];
	$contacto=$dat[3];
	$lineaVis=$dat[4];
	
	$sqlConsulta="select a.producto, a.posicion
		from asignacion_productos_excel_detalle a where a.especialidad='$espe' and a.ciudad='$ciudad'
		and a.contacto='$contacto' and a.linea_mkt='$lineaMkt' and linea='$lineaVis' order by posicion";
	$respConsulta=mysql_query($sqlConsulta);
	$ii=1;
	while($datConsulta=mysql_fetch_array($respConsulta)){
		$codProducto=$datConsulta[0];
		$posicion=$datConsulta[1];
		
		echo "$espe $ciudad $lineaMkt $contacto $lineaVis $codProducto pos: $posicion indice: $ii<BR>";
		if($ii!=$posicion){
			echo "PROBLEMAAAAA-------------";
			
			$sqlUpd="update asignacion_productos_excel_detalle set posicion=$ii where especialidad='$espe' and ciudad='$ciudad'
			and contacto='$contacto' and linea_mkt='$lineaMkt' and linea='$lineaVis' and producto='$codProducto'";
			$respUpd=mysql_query($sqlUpd);
		}

		$ii++;
		
	}
}
?>