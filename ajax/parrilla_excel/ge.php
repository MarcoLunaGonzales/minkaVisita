<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
error_reporting(0);
require("../../conexion.inc");
$ciclo_final   = 5;
$gestion_final = 1010;
$cadena = '';
$cat = array('A','B','C');
$fechaInsercion = date('Y-m-d');
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$sqlParrilla = "SELECT CODIGO,LINEA,AGENCIA,ESPE,CAT,NRO_VISITA,LINEAVISITA, ORDEN,COD_PROD,PROD,CANT_PROD,COD_MAT,MATERIAL,CANT_MATERIAL from parrillas_automatica p where p.ciclo = $ciclo_final and p.gestion = $gestion_final group by p.AGENCIA,p.LINEA, p.ESPE, p.LINEAVISITA, p.CAT, p.nro_visita order by p.AGENCIA,p.LINEA, p.ESPE, p.LINEAVISITA,p.CAT, p.nro_visita";
$respParrilla = mysql_query($sqlParrilla);
while ($datParrilla = mysql_fetch_array($respParrilla)) {
	$codigo = $datParrilla[0];
	$linea = $datParrilla[1];
	$agencia = $datParrilla[2];
	$espe = $datParrilla[3];
	$cat = $datParrilla[4];
	$nroVisita = $datParrilla[5];
	$lineaVisita = $datParrilla[6];

	$sql = "SELECT max(p.codigo_parrilla) from parrilla p";
	$resp = mysql_query($sql);
	$dat = mysql_fetch_array($resp);
	$num_filas = mysql_num_rows($resp);
	if ($num_filas == 0) {
		$codigo = 1000;
	} else {
		$codigo = $dat[0];
		$codigo++;
	}
	$sqlInsert2 = "INSERT INTO parrilla (codigo_parrilla,cod_ciclo,cod_especialidad,categoria_med,codigo_linea, fecha_creacion,fecha_modificacion,numero_visita,agencia,codigo_l_visita,muestras_extra,codigo_gestion) VALUES ($codigo, $ciclo_final, '$espe','$cat',$linea, '$fechaInsercion', '$fechaInsercion', $nroVisita, $agencia, $lineaVisita, 0,$gestion_final)";
	$respInsert = mysql_query($sqlInsert2);

	$sqlDetalle = "SELECT CODIGO,ORDEN,COD_PROD,CANT_PROD,COD_MAT,CANT_MATERIAL from parrillas_automatica p where p.LINEA=$linea and p.AGENCIA=$agencia and p.ESPE='$espe'and p.CAT='$cat' and p.NRO_VISITA=$nroVisita and p.LINEAVISITA=$lineaVisita and p.ciclo = $ciclo_final and p.gestion = $gestion_final";
	$respDetalle = mysql_query($sqlDetalle);
	while ($datDetalle = mysql_fetch_array($respDetalle)) {
		$orden = $datDetalle[1];
		$codProd = $datDetalle[2];
		$cantProd = $datDetalle[3];
		$codMaterial = $datDetalle[4];
		$cantMaterial = $datDetalle[5];

		$sqlInsertDetalle = "INSERT INTO parrilla_detalle (codigo_parrilla, codigo_muestra, cantidad_muestra, codigo_material, cantidad_material, prioridad, observaciones, extra) VALUES ($codigo, '$codProd', $cantProd, '$codMaterial', $cantMaterial, $orden,'',0)";
		$respInsertDetalle = mysql_query($sqlInsertDetalle);
	}

}

echo "ok";
?>