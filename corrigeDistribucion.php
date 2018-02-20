<?php
require("conexion.inc");
$ciclo=11;
$gestion=1008;

$sql="select d.`cod_ciclo`, d.`codigo_gestion`, d.`codigo_linea`, d.`territorio`, d.`cod_visitador`, d.`codigo_producto`, 
	d.cantidad_planificada, d.cantidad_distribuida, d.cantidad_sacadaalmacen
	from `distribucion_productos_visitadores2` d where d.`cod_ciclo`=$ciclo	and d.`codigo_gestion`=$gestion and 
	d.`codigo_linea`<>1021";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codCiclo=$dat[0];
	$codGestion=$dat[1];
	$codLinea=$dat[2];
	$codTerritorio=$dat[3];
	$codVisitador=$dat[4];
	$codProducto=$dat[5];
	$cantPlani=$dat[6];
	$cantDist=$dat[7];
	$cantSacada=$dat[8];
	
	echo "$codLinea $codTerritorio $codVisitador $codProducto $cantPlani $cantDist $cantSacada <br>";
	
	$sqlUpd="update distribucion_productos_visitadores set cantidad_distribuida='$cantDist', cantidad_sacadaalmacen='$cantSacada'
		where `cod_ciclo`='$codCiclo' and `codigo_gestion`='$codGestion' and 
		`codigo_linea`='$codLinea' and territorio='$codTerritorio' and cod_visitador='$codVisitador' and codigo_producto='$codProducto'";
	echo $sqlUpd."<br>";
	$respUpd=mysql_query($sqlUpd);
	
	
}

?>