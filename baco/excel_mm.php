<?php

set_time_limit(0);
require("../conexion.inc");
$fecha_inicio = $_POST['fecha_ini'];
$fecha_final = $_POST['fecha_fin'];
require_once '../lib/excel/PHPExcel.php';
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
ini_set('memory_limit', '128M');


$objPHPExcel = new PHPExcel();
$objPHPExcel->getProperties()->setCreator("Laboratorios Cofar S.A");
$objPHPExcel->getProperties()->setLastModifiedBy("Laboratorios Cofar S.A");
$objPHPExcel->getProperties()->setTitle("Entradas Salidas Muestras Medicas");
$objPHPExcel->getProperties()->setSubject("Entradas Salidas Muestras Medicas");
$objPHPExcel->getProperties()->setDescription("Entradas Salidas Muestras Medicas");

/* ENTRADA ALMACEN */

$objPHPExcel->setActiveSheetIndex(0);

$sql_ingresos = mysql_query(" select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='1000' and i.fecha < '$fecha_final' and i.fecha > '$fecha_inicio' and i.grupo_ingreso=1 order by nro_correlativo desc  ");

$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo Ingreso');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hora');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nombre tipo Ingreso');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Observaciones');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nota Entrega');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'N Correlativo');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Ingreso anulado');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);

$output_ingreso = '';
$codigos_hermes_ingreso = '';

while ($row_ingreso = mysql_fetch_array($sql_ingresos)) {
    $codigo_ingreso_almacen = $row_ingreso[0];
    $fecha_ingreso = $row_ingreso[1];
    $hora_ingreso = $row_ingreso[2];
    $nombre_tipoingreso = $row_ingreso[3];
    $observaciones_ingreso = $row_ingreso[4];
    $nota_entrega_ingreso = $row_ingreso[5];
    $numero_correlativo_ingreso = $row_ingreso[6];
    $ingreso_anulado = $row_ingreso[7];
    $output_ingreso .= $codigo_ingreso_almacen . "," . $fecha_ingreso . "," . $hora_ingreso . "," . $nombre_tipoingreso . "," . $observaciones_ingreso . "," . $nota_entrega_ingreso . "," . $numero_correlativo_ingreso . "," . $ingreso_anulado . ",";
    $codigos_hermes_ingreso .= $codigo_ingreso_almacen . ",";
}

$output_ingreso1 = substr($output_ingreso, 0, -1);
$output_ingreso2 = explode(",", $output_ingreso1);
$output_ingreso_final = array_chunk($output_ingreso2, 8);

$objPHPExcel->getActiveSheet()->fromArray($output_ingreso_final, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:H1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');
$objPHPExcel->getActiveSheet()->setTitle('Entrada Almacen MM');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/**/

/**/

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

$codigos_hermes_ingresos = substr($codigos_hermes_ingreso, 0, -1);
$sql_ingreso_detalle = mysql_query(" select * from ingreso_detalle_almacenes where cod_ingreso_almacen in ($codigos_hermes_ingresos) ");

$output_ingresos_detalle = '';

while ($row_detalle_ingreso = mysql_fetch_array($sql_ingreso_detalle)) {
    $codigo_ingreso_almacen_detalle = $row_detalle_ingreso[0];
    $codigo_material_ingreso_detalle = $row_detalle_ingreso[1];
    $numero_lote_ingreso_detalle = $row_detalle_ingreso[2];
    $fecha_vencimiento_ingreso_detalle = $row_detalle_ingreso[3];
    $cantidad_unitaria_ingreso_detalle = $row_detalle_ingreso[4];
    $cantidad_restante_ingreso_detalle = $row_detalle_ingreso[5];
    $output_ingresos_detalle .= $codigo_ingreso_almacen_detalle . "@" . $codigo_material_ingreso_detalle . "@" . $numero_lote_ingreso_detalle . "@" . $fecha_vencimiento_ingreso_detalle . "@" . $cantidad_unitaria_ingreso_detalle . "@" . $cantidad_restante_ingreso_detalle . "@";
}

$output_ingresos_detalle1 = substr($output_ingresos_detalle, 0, -1);
$output_ingresos_detalle2 = explode("@", $output_ingresos_detalle1);
$output_ingresos_detalle_final = array_chunk($output_ingresos_detalle2, 6);

$objPHPExcel->getActiveSheet()->fromArray($output_ingresos_detalle_final, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:F1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');
$objPHPExcel->getActiveSheet()->setTitle('Entrada Detalle Almacen MM');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/**/

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(2);
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');

$sql_salida = mysql_query("select s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, a.codigo_zeus
		FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='1000' and s.fecha < '$fecha_final' and s.fecha > '$fecha_inicio' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.almacen_destino and s.grupo_salida=1 order by s.nro_correlativo desc");
$objPHPExcel->getActiveSheet()->setCellValue('A1', 'Codigo');
$objPHPExcel->getActiveSheet()->setCellValue('B1', 'Fecha');
$objPHPExcel->getActiveSheet()->setCellValue('C1', 'Hora');
$objPHPExcel->getActiveSheet()->setCellValue('D1', 'Nombre Salida');
$objPHPExcel->getActiveSheet()->setCellValue('E1', 'Descripcion');
$objPHPExcel->getActiveSheet()->setCellValue('F1', 'Nombre Almacen');
$objPHPExcel->getActiveSheet()->setCellValue('G1', 'Observaciones');
$objPHPExcel->getActiveSheet()->setCellValue('H1', 'Estado Salida');
$objPHPExcel->getActiveSheet()->setCellValue('I1', 'N Correlativo');
$objPHPExcel->getActiveSheet()->setCellValue('J1', 'Salida Anulada');
$objPHPExcel->getActiveSheet()->setCellValue('K1', 'Almacen Destino');

$objPHPExcel->getActiveSheet()->getColumnDimension('A')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('B')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('C')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('D')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('E')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('F')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('G')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('H')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('I')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('J')->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension('K')->setAutoSize(true);

while ($row_salida = mysql_fetch_array($sql_salida)) {
    $codigo_salida_almacen = $row_salida[0];
    $fecha_salida = $row_salida[1];
    $hora_salida = $row_salida[2];
    $nombre_tipo_salida = $row_salida[3];
    $descripcion_salida = $row_salida[4];
    $nombre_almacen_salida = $row_salida[5];
    $observaciones_salida = $row_salida[6];
    $estado_salida = $row_salida[7];
    $numero_correlativo_salida = $row_salida[8];
    $salida_anulada = $row_salida[9];
    $almacen_destino_salida = $row_salida[10];
    $output_salida .= $codigo_salida_almacen . "@" . $fecha_salida . "@" . $hora_salida . "@" . $nombre_tipo_salida . "@" . $descripcion_salida . "@" . $nombre_almacen_salida . "@" . $observaciones_salida . "@" . $estado_salida . "@" . $numero_correlativo_salida . "@" . $salida_anulada . "@" . $almacen_destino_salida . "@";
    $codigos_salida_hermes .= $codigo_salida_almacen . ",";
}

$output_salida1 = substr($output_salida, 0, -1);
$output_salida2 = explode("@", $output_salida1);
$output_salida_final = array_chunk($output_salida2, 11);

$objPHPExcel->getActiveSheet()->fromArray($output_salida_final, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->setTitle('Salida Almacen MM');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/**/

$objPHPExcel->createSheet();
$objPHPExcel->setActiveSheetIndex(3);
$objPHPExcel->getActiveSheet()->getTabColor()->setARGB('FF0094FF');

$codigos_salida_hermes2 = substr($codigos_salida_hermes, 0, -1);
//echo(" select * from salida_detalle_almacenes where cod_salida_almacen in ($codigos_salida_hermes2) ");
$sql_salida_detalle = mysql_query(" select * from salida_detalle_almacenes where cod_salida_almacen in ($codigos_salida_hermes2) order by 1 DESC ");

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
$salidas_detalle1 = substr($salidas_detalle, 0, -1);
$salidas_detalle2 = explode(",", $salidas_detalle1);
$salidas_detalle_final = array_chunk($salidas_detalle2, 4);

$objPHPExcel->getActiveSheet()->fromArray($salidas_detalle_final, NULL, 'A2');
$objPHPExcel->getActiveSheet()->getStyle('A1:D1')->getFont()->setBold(true);

$objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());

$objPHPExcel->getActiveSheet()->setTitle('Salida Detalle Almacen');
$objPHPExcel->getActiveSheet()->getProtection()->setSheet(true);
$objPHPExcel->getSecurity()->setLockWindows(true);
$objPHPExcel->getSecurity()->setLockStructure(true);

/**/

$objPHPExcel->setActiveSheetIndex(0);

/**/

header('Content-Type: application/vnd.ms-excel');
header('Content-Disposition: attachment;filename="Entradas_Salidas_Muestras_Medicas.xls"');
header('Cache-Control: max-age=0');

$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
$objWriter->save('php://output');
exit;
?>