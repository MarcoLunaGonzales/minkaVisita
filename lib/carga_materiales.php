<?php
require_once ('../baco/coneccion.php');
$lista_materiales = mssql_query("select cod_material, nombre_material, cod_hermes from materiales where cod_grupo in (12,13,20) ORDER BY NOMBRE_MATERIAL ASC");
?>
<script type="text/javascript" language="javascript" src="lib/listado_materiales_baco.js"></script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th>Código BACO</th>
            <th>Nombre Material</th>
            <th>Código HERMES</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        while ($reg = mssql_fetch_array($lista_materiales)) {
            ?>
        <tr>
            <td><a href="guarda_material_baco.php?codigo=<?php  echo  mb_convert_encoding($reg['0'], "UTF-8") ?>"><?php echo  mb_convert_encoding($reg['0'], "UTF-8") ?></a></td>
            <td><a href="guarda_material_baco.php?codigo=<?php  echo  mb_convert_encoding($reg['0'], "UTF-8") ?>"><?php echo mb_convert_encoding($reg['1'], "UTF-8") ?></a></td>
            <td><?php echo  mb_convert_encoding($reg['2'], "UTF-8") ?></td>
        </tr>
        <?php
        }
        ?>
    <tbody>
</table>