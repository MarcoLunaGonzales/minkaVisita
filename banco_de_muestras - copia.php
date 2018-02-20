<script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
<script type="text/javascript" src="lib/jquery.tablesorter.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("#myTable").tablesorter();
    });
</script>
<style>
    th.headerSortUp {
        background-image: url(imagenes/descd.gif) !important;
        background-position: center right;
        background-color: #3399FF;
    }
    th.headerSortDown {
        background-image: url(imagenes/desc.gif)  !important;
        background-position: center right;
        background-color: #3399FF;
    }
    th.header {
        background-image: url(imagenes/bg.gif);
        cursor: pointer;
        font-weight: bold;
        background-repeat: no-repeat;
        background-position: center right;
        padding-right: 15px;
        border-right: 1px solid #dad9c7;
        margin-left: 1px;
    }
    table td { font-size: 12px }
</style>
<?php
require("conexion.inc");
require("estilos.inc");
?>
<center style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Banca de muestras</center>
<table width="99%" border="1" cellpadding="10" id="myTable" class="tablesorter">
    <thead>
        <tr>
            <th scope="col">Nro.Cont.</th>
            <th scope="col">Ciudad</th>
            <th scope="col">M&eacute;dico</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categor&iacute;a</th>
            <th scope="col">Formulario</th>
            <th scope="col">Estado</th>
        </tr>
    </thead>
    <?php
    $sql_gestion = mysql_query("Select max(codigo_gestion) as gestion from rutero_maestro_cab_aprobado");
    while ($row_gestion = mysql_fetch_assoc($sql_gestion)) {
        $codGestion = $row_gestion['gestion'];
    }

    $sql_medico_lista = mysql_query(" SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab_aprobado rc , rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd,
        medicos m where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_gestion = $codGestion and rc.estado_aprobado = 1 
        and rd.cod_med = m.cod_med and rc.codigo_linea = $global_linea ORDER BY 1 LIMIT 0, 200");

    $count = 1;
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
                <td style="text-align: center"><a href="formulario_banco_muestras.php?cod_medico=<?php echo $codigo_medico ?>">Ir al Banco</a></td>
                <?php $query_llenado = mysql_query("select cod_med from banco_muestras where cod_med = $codigo_medico"); ?>
                <?php $num_query_llenado = mysql_num_rows($query_llenado); ?>
                <td style="text-align: center"><?php
        if ($num_query_llenado == 1): echo "Registro Llenado";
        else: echo "<a href='formulario_banco_muestras.php?cod_medico=$codigo_medico' style='color:red; font-weight:bold'>Registro no llenado</a>";
        endif;
                ?></td>
            </tr>
            <?php
        }
        $count++;
    }
    ?>
</table>
