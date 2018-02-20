<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
    .resul  td { color: #000; font-size: 13px}
</style>
<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

$territorio = $_REQUEST['territorio'];
$estado = $_REQUEST['estado'];
$fecha1_i = $_REQUEST['fecha1'];
$fecha2_i = $_REQUEST['fecha2'];

$fecha1 = explode("/", $fecha1_i);
if (strlen($fecha1[1]) == 1) {
    $fecha1_dia = "0" . $fecha1[1];
} else {
    $fecha1_dia = $fecha1[1];
}
if (strlen($fecha1[0]) == 1) {
    $fecha1_mes = "0" . $fecha1[0];
} else {
    $fecha1_mes = $fecha1[0];
}
$fecha1_f = $fecha1[2] . "-" . $fecha1_mes . "-" . $fecha1_dia;

$fecha2 = explode("/", $fecha2_i);
if (strlen($fecha2[1]) == 1) {
    $fecha2_dia = "0" . $fecha2[1];
} else {
    $fecha2_dia = $fecha2[1];
}
if (strlen($fecha2[0]) == 1) {
    $fecha2_mes = "0" . $fecha2[0];
} else {
    $fecha2_mes = $fecha2[0];
}
$fecha2_f = $fecha2[2] . "-" . $fecha2_mes . "-" . $fecha2_dia;

if ($estado == 1) {
    $sql_medico = mysql_query(" select DISTINCT a.cod_med,a.ap_pat_med,a.ap_mat_med,a.nom_med,a.fecha_nac_med,a.telf_med,a.telf_celular_med,a.email_med,a.hobbie_med,a.estado_civil_med,a.perfil_psicografico_med,a.cod_ciudad,a.cod_catcloseup,a.cod_closeup,a.fecha_registro,a.fecha_pre_aprobado,a.fecha_aprobado,b.cod_especialidad,b.categoria_med,c.sexo,c.edad,c.n_pacientes,c.tiene_preferencia,c.nivel,c.costo,a.categorizacion_medico
        from medicos a, categorias_lineas b, categorizacion_medico c where a.fecha_registro <  '$fecha2_f' and a.fecha_registro > '$fecha1_f' and a.cod_ciudad = $territorio and a.cod_med = c.cod_med and c.cod_med = b.cod_med");
    $titulo_tabla = "Fecha Registro";
    $valor_row = 14;
}

if ($estado == 2) {
    $sql_medico = mysql_query(" select DISTINCT a.cod_med,a.ap_pat_med,a.ap_mat_med,a.nom_med,a.fecha_nac_med,a.telf_med,a.telf_celular_med,a.email_med,a.hobbie_med,a.estado_civil_med,a.perfil_psicografico_med,a.cod_ciudad,a.cod_catcloseup,a.cod_closeup,a.fecha_registro,a.fecha_pre_aprobado,a.fecha_aprobado,b.cod_especialidad,b.categoria_med,c.sexo,c.edad,c.n_pacientes,c.tiene_preferencia,c.nivel,c.costo,a.categorizacion_medico
        from medicos a, categorias_lineas b, categorizacion_medico c where a.fecha_pre_aprobado <  '$fecha2_f' and a.fecha_pre_aprobado > '$fecha1_f' and a.cod_ciudad = $territorio and a.cod_med = c.cod_med and c.cod_med = b.cod_med");
    $titulo_tabla = "Fecha Pre Aprobado";
    $valor_row = 15;
}

if ($estado == 3) {
    $sql_medico = mysql_query(" select DISTINCT a.cod_med,a.ap_pat_med,a.ap_mat_med,a.nom_med,a.fecha_nac_med,a.telf_med,a.telf_celular_med,a.email_med,a.hobbie_med,a.estado_civil_med,a.perfil_psicografico_med,a.cod_ciudad,a.cod_catcloseup,a.cod_closeup,a.fecha_registro,a.fecha_pre_aprobado,a.fecha_aprobado,b.cod_especialidad,b.categoria_med,c.sexo,c.edad,c.n_pacientes,c.tiene_preferencia,c.nivel,c.costo,a.categorizacion_medico
        from medicos a, categorias_lineas b, categorizacion_medico c where a.fecha_aprobado <  '$fecha2_f' and a.fecha_aprobado > '$fecha1_f' and a.cod_ciudad = $territorio and a.cod_med = c.cod_med and c.cod_med = b.cod_med");
    $titulo_tabla = "Fecha Aprobado";
    $valor_row = 16;
}

$sql_ciudad = mysql_query(" select descripcion from ciudades where cod_ciudad = $territorio ");
$ciudad = mysql_result($sql_ciudad, 0, 0)
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte Alta M&eacute;dicos  por Fecha<br /> 
                Inicio: <?php echo $fecha1_i ?> final: <?php echo $fecha2_i ?>
                Territorio : <?php echo $ciudad ?>
            </th>
        </tr>
    </table>
</center>
<center>
    <table class="resul" border="1" width="99%">
        <?php $count = 1; ?>
        <tr>
            <th>&nbsp;</th>
            <th>Ruc</th>
            <th>M&eacute;dico</th>
            <th>Tel&eacute;fonos</th>
            <th>Mail</th>
            <th>Hobbie</th>
            <th>Fecha Cumplea&ntilde;os</th>
            <th>Direcci&oacute;n</th>
            <th>Estado Civil</th>
            <th>Perfil Psicografico</th>
            <th>Especialidad</th>
            <th>Categoria</th>
            <th>Categoria Sistema</th>
            <th>Sexo</th>
            <th>Edad</th>
            <th>Pacientes</th>
            <th>Preferencia</th>
            <th>Nivel</th>
            <th>Costo</th>
            <th>Categoria Closeup</th>
            <th>Codigo Closeup</th>
            <th><?php echo $titulo_tabla; ?></th>
        </tr>
        <?php while ($row = mysql_fetch_array($sql_medico)) { ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row[0]; ?></td>
                <td><?php echo $row[1] . " " . $row[2] . " " . $row[3]; ?></td>
                <td><?php echo $row[5] . " - " . $row[6] ?></td>
                <td><?php echo $row[7] ?></td>
                <td><?php echo $row[8] ?></td>
                <td><?php 
                    $fecha_naci = $row[4];
                    $fecha_naci_final = explode("-",$fecha_naci);
                    echo "<center>".$fecha_naci_final[1]."/".$fecha_naci_final[2]."</center>";
                ?></td>
                <td>
                    <?php
                        $sql_direccion = mysql_query("select direccion from direcciones_medicos where cod_med = $row[0] limit 0,1");
                         if(mysql_result($sql_direccion,0,0) == ''){
                          echo  "-";
                        }else{
                            echo mysql_result($sql_direccion,0,0);
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($row[9] == 1){
                            echo "Soltero(a)";
                        }
                        if($row[9] == 2){
                            echo "Casado(a)";
                        }
                        if($row[9] == 3){
                            echo "Divorciado(a)";
                        }
                        if($row[9] == 4){
                            echo "Viudo(a)";
                        }
                    ?>
                </td>
                <td>
                    <?php
                        if($row[10] == 1){
                            echo "Amable";
                        }
                        if($row[10] == 2){
                            echo "Expresivo";
                        }
                        if($row[10] == 3){
                            echo "Dirigente";
                        }
                        if($row[10] == 4){
                            echo "Analitico";
                        }
                    ?>
                </td>
                <td><?php echo $row[17] ?></td>
                <td><?php echo $row[18] ?></td>
                <td><?php echo $row[25] ?></td>
                <td><?php echo $row[19] ?></td>
                <td><?php 
                    if($row[20] == 1 ){
                        echo "Menor o igual a 30";
                    }
                    if($row[20] == 2 ){
                        echo "Mayor de 31 y menor igual a 50";
                    }
                    if($row[20] == 3 ){
                        echo "Mayor de 51 y menor igual a 60";
                    }
                    if($row[20] == 4 ){
                        echo "Mayor de 60";
                    }
                ?></td>
                <td><?php echo $row[21] ?></td>
                <td><?php echo $row[22] ?></td>
                <td><?php echo $row[23] ?></td>
                <td><?php echo $row[24] ?></td>
                <td><?php echo $row[12]; ?></td>
                <td><?php echo $row[13]; ?></td>
                <td align="center" style="text-align: center"><?php echo $row[$valor_row]; ?></td>
            </tr>
            <?php $count++; ?>
        <?php } ?>
    </table>
</center>
<br />
<center>
    <table border='0'>
        <tr>
            <td>
                <a href='javascript:window.print();'>
                    <img border='no' alt='Imprimir esta' src='imagenes/print.gif' />Imprimir</a>
            </td>
        </tr>
    </table>
</center>