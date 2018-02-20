<?php

header("Content-Type: text/html; charset=UTF-8");
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$territorio = $_REQUEST['territorio'];
$gestion = $_REQUEST['gestion'];
$linea = $_REQUEST['linea'];
$especialidad = $_REQUEST['especialidad'];
$categoria = $_REQUEST['categoria'];
$gestion_final = explode("|", $gestion);



foreach ($categoria as $value) {
    $categoria_aux .= " '" . $value . "',";
}
$categoria_final = substr($categoria_aux, 0, -1);


foreach ($especialidad as $value1) {
    $especialidad_aux .= " '" . $value1 . "',";
}
$especialidad_final = substr($especialidad_aux, 0, -1);


//$arr = array("1"=>$territorio,"2"=>$gestion,"3"=>$linea,"4"=>$especialidad,"5"=>$categoria);

$sql_medico = mysql_query(" SELECT  DISTINCT(CONCAT(a.ap_pat_med,' ',a.ap_mat_med,' ',a.nom_med))  as nombre, a.cod_med from medicos a, rutero_maestro_detalle b,
rutero_maestro_cab c, rutero_maestro d where a.cod_med = b.cod_med and d.cod_contacto = b.cod_contacto and c.cod_rutero = d.cod_rutero
and b.cod_especialidad in ($especialidad_final) and b.categoria_med in ($categoria_final) and c.codigo_gestion = $gestion_final[1] 
and codigo_ciclo = $gestion_final[0] and c.codigo_linea = $linea and a.cod_ciudad = $territorio order by nombre ");

//echo (" SELECT  DISTINCT(CONCAT(a.ap_pat_med,' ',a.ap_mat_med,' ',a.nom_med))  as nombre, a.cod_med from medicos a, rutero_maestro_detalle b,
//rutero_maestro_cab c, rutero_maestro d where a.cod_med = b.cod_med and d.cod_contacto = b.cod_contacto and c.cod_rutero = d.cod_rutero
//and b.cod_especialidad in ($especialidad_final) and b.categoria_med in ($categoria_final) and c.codigo_gestion = $gestion_final[1] 
//and codigo_ciclo = $gestion_final[0] and c.codigo_linea = $linea and a.cod_ciudad = $territorio order by nombre  ");

$cuantos = mysql_num_rows($sql_medico);

if ($cuantos == 0) {
    echo json_encode("<option value='Sin Opcion' disabled>No se encontraron Opciones</option>");
} else {
    while ($row_a = mysql_fetch_assoc($sql_medico)) {
        $codigo = $row_a['cod_med'];
        $nombre_final = $row_a['nombre'];
        $output .= "<option value='" . $codigo . "'> " . $nombre_final . " </option>";
    }
    $output_final = $output;

    echo json_encode($output_final);
}
?>