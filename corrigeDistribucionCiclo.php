<?php
require("conexion.inc");

$sql="select sd.`cod_material`, sv.`codigo_funcionario`, sd.`cantidad_unitaria`, 
	(select m.descripcion from muestras_medicas m where sd.cod_material=m.codigo) as muestra,
	(select concat(f.paterno,' ', f.nombres) from funcionarios f where f.codigo_funcionario=sv.`codigo_funcionario`) as visitador,
	(select f.cod_ciudad from funcionarios f where f.codigo_funcionario=sv.`codigo_funcionario`) as agencia
	from `salida_almacenes` s, `salida_detalle_almacenes` `sd`, `salida_detalle_visitador` sv where
	s.`cod_salida_almacenes`=`sd`.`cod_salida_almacen` and sd.`cod_salida_almacen`=sv.`cod_salida_almacen` and
	s.`salida_anulada`=0 and s.`nro_correlativo`>=12839 and 
	s.`observaciones` like '%SALIDA AUTOMATICA Ciclo: 1 Gestión: 2012-2013 Linea: BPH General%' and 
	s.`grupo_salida`=1";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codItem=$dat[0];
	$codVisitador=$dat[1];
	$cantidadSalida=$dat[2];
	$codCiudad=$dat[5];
	$codLinea=1021;
	
	$upd="update `distribucion_productos_visitadores` set cantidad_distribuida='$cantidadSalida', cantidad_sacadaalmacen='$cantidadSalida'
		where `cod_ciclo`=1 and `codigo_gestion`=1009 and `cod_visitador`='$codVisitador' and 
		`codigo_producto`='$codItem' and `codigo_linea`='$codLinea' and `grupo_salida`=1 and 
		`territorio`='$codCiudad'";
	echo $upd."<br>";
	$respUpd=mysql_query($upd);
}

?>