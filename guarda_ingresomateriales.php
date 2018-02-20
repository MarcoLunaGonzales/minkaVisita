<?php

require("conexion.inc");
if ($global_tipoalmacen == 1) {
    require("estilos_almacenes_central.inc");
} else {
    require("estilos_almacenes.inc");
}
$sql = "select cod_ingreso_almacen from ingreso_almacenes order by cod_ingreso_almacen desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $codigo = 1;
} else {
    $codigo = $dat[0];
    $codigo++;
}
$sql = "select nro_correlativo from ingreso_almacenes where cod_almacen='$global_almacen' and grupo_ingreso='2' order by cod_ingreso_almacen desc";
$resp = mysql_query($sql);
$dat = mysql_fetch_array($resp);
$num_filas = mysql_num_rows($resp);
if ($num_filas == 0) {
    $nro_correlativo = 1;
} else {
    $nro_correlativo = $dat[0];
    $nro_correlativo++;
}
$hora_sistema = date("H:i:s");
$fecha_real = $fecha[6] . $fecha[7] . $fecha[8] . $fecha[9] . "-" . $fecha[3] . $fecha[4] . "-" . $fecha[0] . $fecha[1];
$vector_material = explode(",", $vector_material);
$vector_nrolote = explode(",", $vector_nrolote);
$vector_fechavenci = explode(",", $vector_fechavenci);
$vector_cantidades = explode(",", $vector_cantidades);
$vector_empaques = explode(",", $vector_empaques);

$sqlXInserta="insert into ingreso_almacenes values
($codigo,$global_almacen,$tipo_ingreso,'$fecha_real','$hora_sistema','$observaciones',2,0,'$nota_entrega',
'$nro_correlativo',0,'$orden_compra','$empaques')";

echo $sqlXInserta;

$sql_inserta = mysql_query($sqlXInserta);
for ($i = 0; $i <= $cantidad_material - 1; $i++) {
    $cod_material = $vector_material[$i];
    $numero_lote = $vector_nrolote[$i];
    $fecha_vencimiento = $vector_fechavenci[$i];
    $fecha_sistema_vencimiento = $fecha_vencimiento[6] . $fecha_vencimiento[7] . $fecha_vencimiento[8] . $fecha_vencimiento[9] . "-" . $fecha_vencimiento[3] . $fecha_vencimiento[4] . "-" . $fecha_vencimiento[0] . $fecha_vencimiento[1];
    $cantidad = $vector_cantidades[$i];
	$nroEmpaqueDet=$vector_empaques[$i];
    $txtInserta2="insert into ingreso_detalle_almacenes values
	($codigo,'$cod_material','','$fecha_sistema_vencimiento',$cantidad,$cantidad,$nroEmpaqueDet)";
	
	//echo $txtInserta2;
	$sql_inserta2 = mysql_query($txtInserta2);
	
	if($nroEmpaqueDet>0){
		$cantidadXEmpaque=ceil($cantidad/$nroEmpaqueDet);
		$ii=1;
		while($ii<$nroEmpaqueDet){
			$sqlInsertEti="insert into ingreso_etiquetas (cod_ingreso, etiqueta, cod_material, cantidad) values
			 ($codigo, $ii, $cod_material, $cantidadXEmpaque)";
			$respInsertEti=mysql_query($sqlInsertEti);
			$ii++;
		}
		$cantSaldo=$cantidad-($cantidadXEmpaque*($nroEmpaqueDet-1));
		$sqlInsertEti="insert into ingreso_etiquetas (cod_ingreso, etiqueta, cod_material, cantidad) values
			 ($codigo, $ii, $cod_material, $cantSaldo)";
		$respInsertEti=mysql_query($sqlInsertEti);
	}
	
}





/*DESDE AQUI HACEMOS LA SALIDA AUTOMATICA PARA MARKETING*/
if($tipo_ingreso==1000){
	$sql="select cod_salida_almacenes from salida_almacenes order by cod_salida_almacenes desc";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$num_filas=mysql_num_rows($resp);
	if($num_filas==0)
	{	$codigo=1;
	}
	else
	{	$codigo=$dat[0];
		$codigo++;
	}
	$sql="select nro_correlativo from salida_almacenes where cod_almacen='$global_almacen' and grupo_salida='2' 
	order by cod_salida_almacenes desc";
	$resp=mysql_query($sql);
	$dat=mysql_fetch_array($resp);
	$num_filas=mysql_num_rows($resp);
	if($num_filas==0){	$nro_correlativo=1; }	else {	$nro_correlativo=$dat[0]; $nro_correlativo++;  }
	$fecha=date("d/m/Y");
	$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
	$hora_salida=date("H:i:s");

	$tipo_salida=1008; //MARKETING
	$observaciones="Salida Automatica para entrega a Jefe de Marketing";
	$sql_inserta="insert into salida_almacenes values($codigo,$global_almacen,$tipo_salida,'$fecha_real','$hora_salida','$global_agencia','$global_almacen','$observaciones',0,2,'','',0,0,0,0,'','$nro_correlativo',0)";
	$resp_inserta=mysql_query($sql_inserta);
	for($i=0;$i<=$cantidad_material-1;$i++)
	{	$cod_material=$vector_material[$i];
		$cantidad=1;
		$sql_detalle_ingreso="select id.cod_ingreso_almacen, id.cantidad_restante, id.nro_lote from ingreso_detalle_almacenes id,
		ingreso_almacenes i
		where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$global_almacen' and i.ingreso_anulado=0
		and id.cod_material='$cod_material' and id.cantidad_restante<>0 order by id.cod_ingreso_almacen";
		$resp_detalle_ingreso=mysql_query($sql_detalle_ingreso);
		$cantidad_bandera=$cantidad;
		$bandera=0;
		while($dat_detalle_ingreso=mysql_fetch_array($resp_detalle_ingreso))
		{	$cod_ingreso_almacen=$dat_detalle_ingreso[0];
			$cantidad_restante=$dat_detalle_ingreso[1];
			$nro_lote=$dat_detalle_ingreso[2];
			if($bandera!=1)
			{	if($cantidad_bandera>$cantidad_restante)
				{	$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_restante','2')";
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=0 where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
					$resp_upd_cantidades=mysql_query($upd_cantidades);
				}
				else
				{
					$sql_salida_det_ingreso="insert into salida_detalle_ingreso values('$codigo','$cod_ingreso_almacen','$cod_material','$nro_lote','$cantidad_bandera','$grupo_ingreso')";
					$resp_salida_det_ingreso=mysql_query($sql_salida_det_ingreso);
					$cantidad_a_actualizar=$cantidad_restante-$cantidad_bandera;
					$bandera=1;
					$upd_cantidades="update ingreso_detalle_almacenes set cantidad_restante=$cantidad_a_actualizar where cod_ingreso_almacen='$cod_ingreso_almacen' and cod_material='$cod_material' and nro_lote='$nro_lote'";
					$resp_upd_cantidades=mysql_query($upd_cantidades);
					$cantidad_bandera=$cantidad_bandera-$cantidad_restante;
				}
			}
		}
		$sql_inserta2="insert into salida_detalle_almacenes values($codigo,'$cod_material',$cantidad,'')";
		$resp_inserta2=mysql_query($sql_inserta2);
		//echo $sql_inserta2;
	}
}

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_ingresomateriales.php';
			</script>";
?>