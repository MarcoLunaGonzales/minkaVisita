<?php
require("conexion.inc");

$sql="select d.cod_ciudad, d.cod_mm, d.cantidad from distri_prueba d";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codCiudad=$dat[0];
	$codMM=$dat[1];
	$cantidad=$dat[2];
	$cantBandera=$cantidad;
	
	echo $codCiudad." ".$codMM." ".$cantidad."<br>";
	$sqlDist="select d.cantidad_planificada, d.cod_visitador from distribucion_productos_visitadores d 
		where d.codigo_gestion=1010 and d.cod_ciclo=10 and 
		d.territorio='$codCiudad' and d.codigo_producto='$codMM' and d.codigo_linea=1021 order by cantidad_planificada";
	$respDist=mysql_query($sqlDist);
	while($datDist=mysql_fetch_array($respDist)){
		$cantPlani=$datDist[0];
		$codVis=$datDist[1];
		
		if($cantBandera>$cantPlani){
			echo "distribuye $codVis $cantPlani <br>";
			$cantBandera=$cantBandera-$cantPlani;
			$sqlUpd="update distribucion_productos_visitadores set cantidad_distribuida='$cantPlani' 
			where codigo_gestion=1010 and cod_ciclo=10 and 
			territorio='$codCiudad' and codigo_producto='$codMM' and codigo_linea=1021 and cod_visitador='$codVis'";
			mysql_query($sqlUpd);
		}else{
			echo "distribuye parcial $codVis $cantBandera <br>";
			$sqlUpd="update distribucion_productos_visitadores set cantidad_distribuida='$cantBandera' 
			where codigo_gestion=1010 and cod_ciclo=10 and 
			territorio='$codCiudad' and codigo_producto='$codMM' and codigo_linea=1021 and cod_visitador='$codVis'";
			mysql_query($sqlUpd);
			$cantBandera=0;
		}
		
	}
}

?>