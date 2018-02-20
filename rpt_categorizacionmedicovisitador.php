<style type="text/css">
    .resul  th { color: #000; font-size: 14px}
</style>
<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_reportes_central.inc");

$territorio = $_REQUEST['territorio'];
$codigo_visitador = $_REQUEST['visitador'];

$sql_nombre_territorio = mysql_query(" Select c.cod_ciudad, c.descripcion from ciudades c, funcionarios f  where f.cod_ciudad = c.cod_ciudad and  f.codigo_funcionario =  $codigo_visitador ");
$codigo_ciudad = mysql_result($sql_nombre_territorio, 0, 0);
$nombre_territorio = mysql_result($sql_nombre_territorio, 0, 1);

$sql_visitador = mysql_query(" SELECT CONCAT(nombres,' ',paterno,' ',materno) as nombre from funcionarios where codigo_funcionario = $codigo_visitador  ");
$nombre_visitador = mysql_result($sql_visitador, 0, 0);
?>
<center>
    <table class='textotit'>
        <tr>
            <th>
                Reporte Categorizacion M&eacute;dicos x Visitador <br />
                Territorio : <?php echo $nombre_territorio; ?> <br />
                Visitador : <?php echo $nombre_visitador; ?> <br />
            </th>
        </tr>
    </table>
</center>
<?php
/* ------------------------------------------------------------------------------------------------------------------------------------- */

$sql_codigo_visitadores = mysql_query(" select DISTINCT(rd.`cod_med`), rd.`cod_especialidad`, rd.`categoria_med` from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm, `rutero_maestro_detalle_aprobado` rd
        where rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and  rc.`codigo_ciclo`  in (1,2) and rc.`codigo_gestion`=1009 and rc.`cod_visitador`=$codigo_visitador 
        and rc.estado_aprobado = 1 order by 1; ");

/* ------------------------------------------------------------------------------------------------------------------------------------- */
?>


<center>
    <table class="resul" border="1">
        <tr>
            <th>&nbsp;</th>
            <th>Nombre M&eacute;dico</th>
            <th>Especialidad</th>
            <th>Categoria</th>
            <th>Formulario Llenado</th>
        </tr>
        <?php
        $count = 1;
        while ($row_medicos = mysql_fetch_array($sql_codigo_visitadores)) {
            $codigo_medico = $row_medicos[0];
            $sql_datos_medico = "Select CONCAT(nom_med,' ',ap_pat_med,' ',ap_mat_med) as nombre from medicos where cod_med = $codigo_medico order by nombre";
            $resp_sql_datos_medico = mysql_query($sql_datos_medico);
            while ($row_m = mysql_fetch_assoc($resp_sql_datos_medico)) {
                ?>
                <tr>
                    <td><?php echo $count; ?></td>
                    <td><?php echo $row_m['nombre']; ?></td>
                    <td align="center"><?php echo $row_medicos[1]; ?></td>
                    <td align="center"><?php echo $row_medicos[2]; ?></td>
                    <td>
                        <?php $query_llenado = mysql_query("select cod_med from categorizacion_medico where cod_med = $codigo_medico"); ?>
                        <?php $num_query_llenado = mysql_num_rows($query_llenado); ?>
                        <?php
                        if ($num_query_llenado == 1):
                           echo "Formulario llenado";
                        else:
                            echo "<span style='color:red; font-weight:bold'>Formulario NO llenado</span>";
                        endif;
                        ?>
                    </td>
                </tr>
                <?php
            }
            $count++;
        }
        ?>
    </table>
</center>

<br /><center><table border='0'><tr><td><a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a></td></tr></table>