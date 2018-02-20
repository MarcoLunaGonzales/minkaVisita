<?php
require_once ('../conexion.inc');
error_reporting(0);

$sql_medico_lista = mysql_query("SELECT m.cod_med, c.descripcion ,CONCAT(m.ap_pat_med,' ',m.ap_mat_med, ' ', m.nom_med) as nombre, cl.cod_especialidad, cl.categoria_med, cl.codigo_linea, l.nombre_linea from medicos m, ciudades c, categorias_lineas cl, lineas l where m.cod_med = cl.cod_med and c.cod_ciudad = m.cod_ciudad and l.codigo_linea = cl.codigo_linea and m.estado_registro = 1 ORDER BY 3 ASC");

$count = 1;
?>
<script type="text/javascript" language="javascript" src="lib/listado_materiales_baco4.js"></script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th scope="col">RUC.</th>
            <th scope="col">Ciudad</th>
            <th scope="col">M&eacute;dico</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categor&iacute;a</th>
            <th scope="col">L&iacute;nea</th>
            <th scope="col">Adicionar</th>
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
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        while ($row = mysql_fetch_assoc($sql_medico_lista)) {
            ?>
            <tr>
                <td><?php echo $row['cod_med'] ?></td>
                <td><?php echo $row['descripcion'] ?></td>
                <td><?php echo $row['nombre'] ?></td>
                <td style="text-align: center"><?php echo $row['cod_especialidad'] ?></td>
                <td style="text-align: center"><?php echo $row['categoria_med'] ?></td>
                <td style="text-align: center"><?php echo $row['nombre_linea'] ?></td>
                <td style="text-align: center"><a href="adicionar_producto_medico_detalle.php?cod_med=<?php echo $row['cod_med'] ?>&linea=<?php echo $row['codigo_linea'] ?>">Adicionar</a></td>
            <?php
        }
        ?>
    </tbody>
</table>