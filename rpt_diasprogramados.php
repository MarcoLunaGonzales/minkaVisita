<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
    .resul  td { color: #000; font-size: 13px}
</style>
<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

$codigo_med = $_REQUEST['codigo_med'];
$gestion = $_REQUEST['gestion'];
$linea = $_REQUEST['linea'];

$gestion_final = explode("|", $gestion);

$sql_datos_medico = mysql_query(" SELECT DISTINCT(CONCAT(a.nom_med,' ',a.ap_pat_med,' ',a.ap_mat_med)) as nombre , 
    a.cod_catcloseup , c.desc_especialidad, b.categoria_med, c.cod_especialidad, a.cod_med from medicos a , rutero_maestro_detalle_aprobado b, especialidades c, rutero_maestro_cab_aprobado d, 
    rutero_maestro_aprobado e where a.cod_med = b.cod_med and b.cod_especialidad = c.cod_especialidad and b.cod_contacto = e.cod_contacto and e.cod_rutero = d.cod_rutero and d.codigo_linea = $linea
    and d.codigo_gestion = $gestion_final[1] and d.codigo_ciclo = $gestion_final[0] and a.cod_med in ($codigo_med) order by nombre ASC  ");


//echo(" SELECT DISTINCT(CONCAT(a.nom_med,' ',a.ap_pat_med,' ',a.ap_mat_med)) as nombre , 
//    a.cod_catcloseup , c.desc_especialidad, b.categoria_med, c.cod_especialidad, a.cod_med from medicos a , rutero_maestro_detalle_aprobado b, especialidades c, rutero_maestro_cab_aprobado d, 
//    rutero_maestro_aprobado e where a.cod_med = b.cod_med and b.cod_especialidad = c.cod_especialidad and b.cod_contacto = e.cod_contacto and e.cod_rutero = d.cod_rutero and d.codigo_linea = $linea
//    and d.codigo_gestion = $gestion_final[1] and d.codigo_ciclo = $gestion_final[0] and a.cod_med in ($codigo_med) order by nombre ASC ");
?>


<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte D&iacute;as Programados Visita Por M&eacute;dico <br /> <br /> 
        </tr>
    </table>
</center>
<center>
    <table class="resul" border="1" width="70%">
        <tr>
            <th>&nbsp;</th>
            <th>M&eacute;dico</th>
            <th>Especialidad</th>
            <th>Categoria</th>
            <th>Categoria CloseUp</th>
            <th>Contenido Por Grilla</th>
            <th>D&iacute;as programados de visita</th>
        </tr>
        <?php
        $count = 1;

        while ($row_nom = mysql_fetch_array($sql_datos_medico)) {
//            $sql_grilla = mysql_query(" SELECT COUNT(cod_especialidad) from grilla_detalle where cod_especialidad = '$row_nom[4]' and cod_categoria = '$rowro_nom[3]' ");
            $sql_grilla = mysql_query(" SELECT COUNT(a.codigo_grilla)  from grilla a, grilla_detalle b where a.codigo_grilla = b.codigo_grilla and a.codigo_linea = $linea 
                    and b.cod_categoria = '$row_nom[3]' and b.cod_especialidad = '$row_nom[4]' ");
//            echo(" SELECT COUNT(a.codigo_grilla)  from grilla a, grilla_detalle b where a.codigo_grilla = b.codigo_grilla and a.codigo_linea = $linea 
//                    and b.cod_categoria = '$row_nom[3]' and b.cod_especialidad = '$row_nom[4]' ");
            $cuantos_grilla = mysql_result($sql_grilla, 0, 0);

            $sql_dias = mysql_query(" SELECT c.dia_contacto, c.turno from rutero_maestro_detalle_aprobado a , rutero_maestro_cab_aprobado b, rutero_maestro_aprobado c where 
                a.cod_contacto = c.cod_contacto and c.cod_rutero = b.cod_rutero and b.codigo_linea = $linea and b.codigo_ciclo = $gestion_final[0] and b.codigo_gestion = $gestion_final[1] and a.cod_med = $row_nom[5] ; ");
            $dias_finales = "";
            while ($row_dias = mysql_fetch_array($sql_dias)) {
                $dias_finales .= $row_dias[0] . " " . $row_dias[1] . " - ";
            }
            $dias_finales = substr($dias_finales, 0, -2);
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row_nom[0]; ?></td>
                <td><?php echo $row_nom[2]; ?></td>
                <td align="center" style="text-align: center"><?php echo $row_nom[3]; ?></td>
                <td align="center" style="text-align: center"><?php echo $row_nom[1]; ?></td>
                <td align="center" style="text-align: center"><?php echo $cuantos_grilla; ?></td>
                <td width="25%" style="text-align: center"><?php echo $dias_finales; ?></td>
            </tr>
            <?php
            $count++;
        }
        ?>
    </table>
</center>

<br /><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>