<?php

set_time_limit(0);
//require("conexion.inc");
require("conexion-dir.inc");
header ( "Content-Type: text/html; charset=UTF-8" );
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$fecha_inicio = $_POST['fecha_ini'];
$fecha_final = $_POST['fecha_fin'];

error_reporting(0);

date_default_timezone_set('America/Bolivia');

require_once 'lib/excel/PHPExcel.php';


$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Laboratorios Cofar S.A");
$objPHPExcel->getProperties()->setLastModifiedBy("Laboratorios Cofar S.A");
$objPHPExcel->getProperties()->setTitle("Entradas Salidas Material Apoyo");
$objPHPExcel->getProperties()->setSubject("Entradas Salidas Material Apoyo");
$objPHPExcel->getProperties()->setDescription("Entradas Salidas Material Apoyo");

/* ENTRADA ALMACEN */

$objPHPExcel->setActiveSheetIndex(0);

$sql_ingresos = mysql_query("SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, i.cod_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado , i.cod_tipoingreso, i.cod_orden_compra FROM ingreso_almacenes i, tipos_ingreso ti where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='1000' and i.grupo_ingreso=2 and i.fecha <= '$fecha_final' and i.fecha >= '$fecha_inicio' and i.ingreso_anulado <> 1 order by i.nro_correlativo desc  ");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hora');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Tipo Ingreso');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Observaciones');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nota Entrega');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'N Correlativo');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Salida Anulada');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'N Orden Compra');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

while ($row = mysql_fetch_array($sql_ingresos)) {
    $codigo_hermes = $row[0];
    $fecha = $row[1];
    $hora = $row[2];
    $nombre = $row[3];
    
	$obs = $row[4];
    $permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ .()"; 
	$nuevaObs="";
	$j=0;
	for ($i=0; $i<strlen($obs); $i++){ 
		$pos=strpos($permitidos, $obs[$i]);
		if($pos===false){
		}else{
			$nuevaObs=$nuevaObs.$obs[$i];
		}
	} 
	
	$nota = $row[5];
    $n_correlativo = $row[6];
    $anulado = $row[7];
    $codigo_tipo_ingreso = $row[8];
    $codigo_orden_compra = $row[9];
    $output_ingreso .= $codigo_hermes . "," . $fecha . "," . $hora . "," . $nombre . "," . $nuevaObs . "," . $nota . "," . $n_correlativo . "," . $anulado . "," . $codigo_orden_compra . ",";
    $codigos_hermes .= $codigo_hermes . ",";
}
$output_ingreso = substr($output_ingreso, 0, -1);
$output_ingreso = explode(",", $output_ingreso);
$output_ingreso = array_chunk($output_ingreso, 9);

$objPHPExcel->getActiveSheet()->fromArray($output_ingreso, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');
$objPHPExcel->getActiveSheet()->setTitle('Entrada Almacen');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/* FIN ENTRADA ALMACEN */

/* ENTRADA DETALLE ALMACEN */
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(1);

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo Ingreso');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Codigo Material');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'N Lote');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Fecha Vencimiento');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Cantidad Unitaria');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Cantidad Restante');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);

$codigos_hermes = substr($codigos_hermes, 0, -1);
$sql_ingreso_detalle = mysql_query(" SELECT * from ingreso_detalle_almacenes where cod_ingreso_almacen in ($codigos_hermes) order by 1 DESC ");
while ($row_ingreso_detalle = mysql_fetch_array($sql_ingreso_detalle)) {
    $cod_ingreso_almacen = $row_ingreso_detalle[0];
    $cod_material = $row_ingreso_detalle[1];
    $nro_lote = $row_ingreso_detalle[2];
    $fecha_vencimiento = $row_ingreso_detalle[3];
    $cantidad_unitaria = $row_ingreso_detalle[4];
    $cantidad_restante = $row_ingreso_detalle[5];
    $ingresos_detalle .= $cod_ingreso_almacen . "," . $cod_material . "," . $nro_lote . "," . $fecha_vencimiento . "," . $cantidad_unitaria . "," . $cantidad_restante . ",";
}
$ingresos_detalle = substr($ingresos_detalle, 0, -1);
$ingresos_detalle = explode(",", $ingresos_detalle);
$ingresos_detalle = array_chunk($ingresos_detalle, 6);

$objPHPExcel->getActiveSheet()->fromArray($ingresos_detalle, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');
$objPHPExcel->getActiveSheet()->setTitle('Entrada Detalle Almacen');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/* FIN ENTRADA DETALLE ALMACEN */

/* SALIDA ALMACEN */
$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');

$sql_salida = mysql_query("SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='1000' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida=2 and s.fecha <= '$fecha_final' and s.fecha >= '$fecha_inicio' and s.salida_anulada <> 1 order by s.nro_correlativo desc");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hora');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nombre Salida');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Observaciones');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nota Entrega');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'N Correlativo');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Salida Anulada');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'Salida Almacen');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);

while ($row_s = mysql_fetch_array($sql_salida)) {
    $codigo_s = $row_s[0];
    $fecha_s = $row_s[1];
    $hora_s = $row_s[2];
    $nombre_s = $row_s[3];
    $obs_s = $row_s[6];
	
	$permitidos = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789-_ .()/"; 
	$nuevaObs="";
	$j=0;
	for ($i=0; $i<strlen($obs_s); $i++){ 
		$pos=strpos($permitidos, $obs_s[$i]);
		if($pos===false){
		}else{
			$nuevaObs=$nuevaObs.$obs_s[$i];
		}
	} 
	
    $nota_s = $row_s[5];
    $n_correlativo_s = $row_s[8];
    $anulado_s = $row_s[9];
    $salida_almacen = $row_s[10];
    $output_salida .= $codigo_s . "@" . $fecha_s . "@" . $hora_s . "@" . $nombre_s . "@" . $nuevaObs . "@" . $nota_s . "@" . $n_correlativo_s . "@" . $anulado_s . "@" . $salida_almacen . "@";
    $codigos_salida_hermes .= $codigo_s . ",";
}
$output_salida = substr($output_salida, 0, -1);
$output_salida = explode("@", $output_salida);
$output_salida = array_chunk($output_salida, 9);

$objPHPExcel->getActiveSheet()->fromArray($output_salida, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:I1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->setTitle('Salida Almacen');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/* FIN SALIDA ALMACEN */

/* SALIDA DETALLE ALMACEN */

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');

$codigos_salida_hermes = substr($codigos_salida_hermes, 0, -1);
$sql_salida_detalle = mysql_query(" SELECT * from salida_detalle_almacenes where cod_salida_almacen in ($codigos_salida_hermes) order by 1 DESC ");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo Salida');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Codigo Material');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Cantidad Unitaria');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Observaciones');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);

while ($row_salida_detalle = mysql_fetch_array($sql_salida_detalle)) {
    $cod_salida_almacens = $row_salida_detalle[0];
    $cod_materials = $row_salida_detalle[1];
    $cantidad_unitarias = $row_salida_detalle[2];
    $observacioness = $row_salida_detalle[3];
    $salidas_detalle .= $cod_salida_almacens . "," . $cod_materials . "," . $cantidad_unitarias . "," . $observacioness . ",";
}
$salidas_detalle = substr($salidas_detalle, 0, -1);
$salidas_detalle = explode(",", $salidas_detalle);
$salidas_detalle = array_chunk($salidas_detalle, 4);

$objPHPExcel->getActiveSheet()->fromArray($salidas_detalle, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->setTitle('Salida Detalle Almacen');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/* FIN SALIDA DETALLE ALMACEN */

$objPHPExcel->setActiveSheetIndex(0);

/**/

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Entradas_Salidas_Material_Apoyo.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>