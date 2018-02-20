<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$codigo_med = $_POST['medicos'];

$sql_direccion = mysql_query("SELECT direccion from direcciones_medicos where cod_med = $codigo_med");
$direcicones = '';
while ($row_direcciones = mysql_fetch_array($sql_direccion)) {
	$direcicones .= $row_direcciones[0].",";
}
$sql_espe_cat = mysql_query("SELECT cod_especialidad, categoria_med  from categorias_lineas where cod_med = $codigo_med and codigo_linea = 1021");
$especialidad = mysql_result($sql_espe_cat, 0, 0);
$categoria    = mysql_result($sql_espe_cat, 0, 1);

$arr = array('direcciones' => $direcicones, 'especialidad' => $especialidad, 'categoria' => $categoria );
echo json_encode($arr);


?>
