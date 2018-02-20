<?php

require("conexion.inc");

$fechaIni="2016-04-01";
$fechaFin="2016-04-30";

$sqlUpd="update ingreso_detalle_almacenes set nro_lote='' where cod_ingreso_almacen in 
	(SELECT cod_ingreso_almacen from `ingreso_almacenes` 
	where `cod_almacen`=1000 and `cod_tipoingreso` in (1009,1006,1010) and 
	`fecha` BETWEEN '$fechaIni' and '$fechaFin' and `grupo_ingreso`=1)";
$respUpd=mysql_query($sqlUpd);

$sql="SELECT id.`cod_ingreso_almacen`, id.`cod_material` from `ingreso_almacenes` i, `ingreso_detalle_almacenes`  id 
where i.`cod_almacen`=1000 and i.`cod_tipoingreso` in (1009,1006,1010) and i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
i.`fecha` BETWEEN '$fechaIni' and '$fechaFin' and id.`nro_lote`='' and i.`grupo_ingreso`=1";

$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){

	$codIng=$dat[0];
	$codMM=$dat[1];	
	//buscamos lotes

	$sqlLote="SELECT id.`nro_lote`, id.`fecha_vencimiento` from `ingreso_almacenes` i, `ingreso_detalle_almacenes`  id 
	where i.`cod_almacen`=1000 and i.`cod_tipoingreso`=1000 and i.`cod_ingreso_almacen`=id.`cod_ingreso_almacen` and 
	i.`fecha` between '2008-02-01' and '$fechaIni' and id.`cod_material`='$codMM' and i.`grupo_ingreso`=1 
	order by i.`cod_ingreso_almacen` desc limit 0,1";
	//echo $sqlLote;

	$lote="";

	$respLote=mysql_query($sqlLote);

	while($datLote=mysql_fetch_array($respLote)){

		$lote=$datLote[0];

		$fecha=$datLote[1];

	}



	echo "$codIng $codMM $lote $fecha<br>";

	

	$sqlUpd="UPDATE ingreso_detalle_almacenes set nro_lote='$lote', fecha_vencimiento='$fecha' where cod_ingreso_almacen='$codIng' and cod_material='$codMM'";

	//echo $sqlUpd;

	$respUpd=mysql_query($sqlUpd);	

	

}





?>