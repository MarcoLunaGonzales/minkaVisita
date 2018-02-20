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
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte Cobertura por M&eacute;dico <br /> <br /> 
            </th>
        </tr>
    </table>
</center>
<center>
    <table class="resul" border="1" width="90%">
        <tr>
            <th>&nbsp;</th>
            <th>M&eacute;dico</th>
            <th>Especialidad</th>
            <th>Categoria</th>
            <th>Categoria CloseUp</th>
            <th>Contenido Por Grilla</th>
            <th>Contactos reportados  3 ciclos anteriores</th>
            <th>Cont. Progamados</th>
            <th>Cont. Efectuados</th>
            <th>%</th>
        </tr>
        <?php
        $count = 1;
        $countt = 0;
        while ($row_nom = mysql_fetch_array($sql_datos_medico)) {
            $sql_grilla = mysql_query(" SELECT COUNT(a.codigo_grilla)  from grilla a, grilla_detalle b where a.codigo_grilla = b.codigo_grilla and a.codigo_linea = $linea 
                    and b.cod_categoria = '$row_nom[3]' and b.cod_especialidad = '$row_nom[4]' ");
            $cuantos_grilla = mysql_result($sql_grilla, 0, 0);
            $codigos_medicos = explode(",", $codigo_med);

            $sql_contactos_efectuados = mysql_query(" Select COUNT(a.cod_ciclo) from rutero a, rutero_detalle b where a.cod_contacto = b.cod_contacto and b.cod_med = $codigos_medicos[$countt] 
                    and a.cod_ciclo = $gestion_final[0] and a.codigo_gestion = $gestion_final[1]  ");
            $contactos_efectuados = mysql_result($sql_contactos_efectuados, 0, 0);

            $sql_contactos_programados = mysql_query(" SELECT COUNT(a.cod_ciclo) from rutero_utilizado a , rutero_detalle_utilizado b where a.cod_contacto = b.cod_contacto and b.cod_med = $codigos_medicos[$countt]
                    and a.cod_ciclo = $gestion_final[0] and a.codigo_gestion = $gestion_final[1] ");
            $contactos_programados = mysql_result($sql_contactos_programados, 0, 0);
            
            $sql_grillas = mysql_query(" select cod_linea, codigo_gestion from ciclos where codigo_gestion = $gestion_final[1] and cod_ciclo < $gestion_final[0] GROUP BY cod_ciclo ORDER BY cod_ciclo DESC limit 4  ");
            $num_grillas = mysql_num_rows($sql_grillas);
            ?>
            <tr>
                <td><?php echo $count; ?></td>
                <td><?php echo $row_nom[0]; ?></td>
                <td><?php echo $row_nom[2]; ?></td>
                <td align="center" style="text-align: center"><?php echo $row_nom[3]; ?></td>
                <td align="center" style="text-align: center"><?php echo $row_nom[1]; ?></td>
                <td align="center" style="text-align: center"><?php echo $cuantos_grilla; ?></td>
                <td width="30%" style="text-align: center">.<?php echo $num_grillas; ?></td>
                <td style="text-align: center"><?php echo $contactos_efectuados; ?></td>
                <td style="text-align: center"><?php echo $contactos_programados; ?></td>
                <td style="text-align: center"><?php echo (($contactos_programados / $contactos_efectuados ) * 100); ?></td>
            </tr>
            <?php
            $count++;
            $countt++;
        }
        ?>
    </table>
</center>
<br /><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>