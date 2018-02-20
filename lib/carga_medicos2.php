<?php
require_once ('../conexion.inc');
$sql_gestion = mysql_query("Select max(codigo_gestion) as gestion from rutero_maestro_cab_aprobado");
while ($row_gestion = mysql_fetch_assoc($sql_gestion)) {
    $codGestion = $row_gestion['gestion'];
}

$sql_llenados = mysql_query("select cod_med from banco_muestras");
while ($row_bm = mysql_fetch_array($sql_llenados)) {
    $bm_codigos .= $row_bm[0] . ",";
}

$bm_codigos_finales = substr($bm_codigos, 0, -1);

$sql_medico_lista = mysql_query(" SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab_aprobado rc , rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd,
        medicos m where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_gestion = $codGestion and rc.estado_aprobado = 1 
        and rd.cod_med = m.cod_med and m.cod_med in ($bm_codigos_finales) and rc.codigo_linea = $global_linea ORDER BY 1 ");

$count = 1;
?>
<script type="text/javascript" language="javascript" src="lib/listado_materiales_baco.js"></script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th scope="col">Nro.Cont.</th>
            <th scope="col">Ciudad</th>
            <th scope="col">M&eacute;dico</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categor&iacute;a</th>
            <th scope="col">Detalle</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        while ($row = mysql_fetch_assoc($sql_medico_lista)) {
            $codigo_medico = $row['cod_med'];
            $sql_datos_medico = "Select m.ap_pat_med, m.ap_mat_med, m.nom_med, c.descripcion from medicos m, ciudades c where cod_med = $codigo_medico and m.cod_ciudad = c.cod_ciudad order by ap_pat_med";
            $resp_sql_datos_medico = mysql_query($sql_datos_medico);
            while ($row_m = mysql_fetch_array($resp_sql_datos_medico)) {
                ?>
                <tr>
                    <td style="text-align: center"><?php echo $count ?></td>
                    <td><?php echo $row_m[3] ?></td>
                    <td><?php echo $row_m[0] ?> <?php echo $row_m[1] ?> <?php echo $row_m[2] ?></td>
                    <td style="text-align: center"><?php echo $row['cod_especialidad'] ?></td>
                    <td style="text-align: center"><?php echo $row['categoria_med'] ?></td>
                    <td style="text-align: center"><a href="formulario_banco_muestras2.php?cod_medico=<?php echo $codigo_medico ?>">Ir al detalle</a></td>
                </tr>
                <?php
            }
            $count++;
        }
        ?>
    <tbody>
</table>