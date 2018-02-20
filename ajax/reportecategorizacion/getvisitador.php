<?php

header("Content-Type: text/html; charset=UTF-8");
set_time_limit(0);
require("../../conexion.inc");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$territorio = $_GET['territorio'];

$territorio_final = implode(",", $territorio);

$sql_medico = mysql_query(" SELECT DISTINCT codigo_funcionario, CONCAT(nombres,' ',paterno,' ',materno) as nombre from funcionarios where cod_ciudad in ($territorio_final) and cod_cargo = 1011 and estado = 1 ");

//echo (" SELECT DISTINCT m.cod_med, CONCAT(m.nom_med,' ',m.ap_pat_med,' ',m.ap_mat_med) as nombre from medicos m, rutero_maestro_detalle_aprobado rmd, 
//    rutero_maestro_aprobado rm, rutero_maestro_cab_aprobado rmc where m.cod_med = rmd.cod_med and rmd.cod_contacto = rm.cod_contacto and rm.cod_rutero = rmc.cod_rutero and
//    m.cod_ciudad in ($territorio_final) and rmc.codigo_ciclo in (1,2) and rmc.codigo_gestion = 1009 ORDER BY 2 ");


$cuantos = mysql_num_rows($sql_medico);

if ($cuantos == 0) {
    echo json_encode("<option value='Sin Opcion' disabled>No se encontraron Opciones</option>");
} else {
    while ($row_a = mysql_fetch_assoc($sql_medico)) {
        $codigo = $row_a['codigo_funcionario'];
        $nombre_final = $row_a['nombre'];
        $output .= "<option value='" . $codigo . "'> " . $nombre_final . " </option>";
    }
    $output_final = $output;

    echo json_encode($output_final);
}
?>