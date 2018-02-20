<?php

require("conexion.inc");
$medico = 7385;
$territorio = 118;
$sql_cod_med_categorizacion = mysql_query(" select * from categorizacion_medico where cod_med = $medico ");

while ($row_c_m_c = mysql_fetch_array($sql_cod_med_categorizacion)) {
    $cod_med_cat = $row_c_m_c[1];
    $edad = $row_c_m_c[3];
    $pacientes = $row_c_m_c[4];
    $prescriptiva = $row_c_m_c[5];
    $nivel = $row_c_m_c[6];
//    $sql_especialidad = mysql_query(" select cod_especialidad from categorias_lineas where cod_med = $cod_med_cat and codigo_linea = 1021 ");
    $sql_especialidad = mysql_query("SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med, m.categorizacion_medico from rutero_maestro_cab_aprobado rc ,
        rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, medicos m where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_ciclo = 7  
        and rc.codigo_gestion = 1009 and rc.estado_aprobado = 1 and rd.cod_med = m.cod_med and m.cod_ciudad = $territorio and m.cod_med = $medico ORDER BY 1");
    while ($row_especialidad = mysql_fetch_array($sql_especialidad)) {
        $especialidad = $row_especialidad[1];
        $ponderacion_especialidad = mysql_query("Select ponderacion from especialidades_ponderacion where especialidad = '$especialidad' ");
        $num_ponderacion_especialidad = mysql_num_rows($ponderacion_especialidad);

        if ($num_ponderacion_especialidad >= 1) {
            while ($row_espe = mysql_fetch_assoc($ponderacion_especialidad)) {
                $ponderacion_especialidad_final = $row_espe['ponderacion'];
            }
        } else {
            $ponderacion_especialidad_final = 2;
        }

        if ($edad == 0) {
            $ponderacion_edad = 0;
        }
        if ($edad == 2) {
            $ponderacion_edad = 4;
        }
        if ($edad == 3) {
            $ponderacion_edad = 2;
        }
        if ($edad == 1 || $edad == 4) {
            $ponderacion_edad = 1;
        }

        if ($pacientes < 8) {
            $ponderacion_pacientes = 2;
        }
        if ($pacientes < 12 && $pacientes >= 8) {
            $ponderacion_pacientes = 4;
        }
        if ($pacientes < 18 && $pacientes >= 12) {
            $ponderacion_pacientes = 6;
        }
        if ($pacientes >= 18) {
            $ponderacion_pacientes = 8;
        }

        if ($prescriptiva == 'Alta') {
            $ponderacion_prescrptiva = 4;
        }
        if ($prescriptiva == 'Media') {
            $ponderacion_prescrptiva = 2;
        }
        if ($prescriptiva == 'Baja') {
            $ponderacion_prescrptiva = 0;
        }

        if ($nivel == 'Alta') {
            $ponderacion_nivel = 3;
        }
        if ($nivel == 'Media') {
            $ponderacion_nivel = 2;
        }
        if ($nivel == 'Baja') {
            $ponderacion_nivel = 1;
        }

        $categoria_medico_sistema = $ponderacion_especialidad_final + $ponderacion_pacientes + $ponderacion_prescrptiva + $ponderacion_nivel + $ponderacion_edad;
        if ($categoria_medico_sistema < 12) {
            $categoria_medico_sistea_final = 'D';
        }
        if ($categoria_medico_sistema >= 15) {
            $categoria_medico_sistea_final = 'C';
        }
        if ($categoria_medico_sistema >= 19) {
            $categoria_medico_sistea_final = 'B';
        }
        if ($categoria_medico_sistema >= 21) {
            $categoria_medico_sistea_final = 'A';
        }
        if ($categoria_medico_sistema >= 23) {
            $categoria_medico_sistea_final = 'AA';
        }
        if ($categoria_medico_sistema >= 27) {
            $categoria_medico_sistea_final = 'AAA';
        }

        mysql_query(" update medicos set categorizacion_medico = '$categoria_medico_sistea_final' where cod_med = $cod_med_cat ");
//        echo(" update medicos set categorizacion_medico = '$categoria_medico_sistea_final' where cod_med = $cod_med_cat ")."; <br />";

        echo $cod_med_cat . " " . $especialidad  . " " . $categoria_medico_sistea_final . " - " . $ponderacion_especialidad_final . " - " . $ponderacion_edad . " - " . $ponderacion_pacientes . " - " . $ponderacion_prescrptiva . " - " . $ponderacion_nivel . "<br />";
    }
}
?>