<?php

header("Content-Type: text/html; charset=UTF-8");
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$territorio = $_REQUEST['territorio'];
$linea = $_REQUEST['linea'];

$territorio = implode(",", $territorio);

$sql_ge = mysql_query(" select codigo_grupo_especial, nombre_grupo_especial from grupo_especial where agencia in ($territorio) and codigo_linea='$linea' order by nombre_grupo_especial");

$cuantos = mysql_num_rows($sql_ge);

if ($cuantos == 0) {
    echo json_encode("<option value='Sin Opcion' disabled>No se encontraron Opciones</option>");
} else {
    while ($row_a = mysql_fetch_array($sql_ge)) {
        $codigo_grupo = $row_a[0];
        $nombre_grupo = $row_a[1];
        $output .= "<option value='" . $codigo_grupo . "'> " . $nombre_grupo . " </option>";
    }
    $output_final = $output;

    echo json_encode($output_final);
}
?>