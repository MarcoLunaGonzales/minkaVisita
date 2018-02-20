<?php
header ( "Content-Type: text/html; charset=UTF-8" );
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$nombre = $_REQUEST['nombre_farmacia'];
//$nombre = utf8_encode($_REQUEST['nombre_farmacia']);
$puesto = $_REQUEST['puesto'];

$num_puesto = substr($puesto, -1);

$sql_select = "select a.nombre from categorias_venta a, fclientes b where b.nombre = '$nombre' and b.cat_ventaetica = a.cod_categoria";

$resp_sql_select = mysql_query($sql_select);
while ($row = mysql_fetch_assoc($resp_sql_select)) {
    $nom = $row['nombre'];
}

$arr = array("nombre" => $nom, "puesto" => $num_puesto);
echo json_encode($arr);
?>
