<?php
require_once ('../conexion.inc');
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$lista_materiales = mysql_query("select a.codigo_material, a.descripcion_material,a.url_imagen,a.codigo_linea, b.nombre_linea from material_apoyo a , lineas b where a.estado = 'Activo' and a.codigo_material <> 0 and a.codigo_linea = b.codigo_linea ORDER BY descripcion_material ASC");
?>
<script type="text/javascript" language="javascript" src="lib/listado_materiales_baco.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>
<link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>

<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox();
        $(".various").fancybox({
            fitToView	: true,
            width		: '90%',
            height		: '70%',
            autoSize	: false,
            closeClick	: true,
            openEffect	: 'none',
            closeEffect	: 'none',
            modal       : true,
            'afterClose': function () {
                //                window.location.reload();
                // alert($(this).attr('class'))
                
            },
            afterLoad: load
        });
        function load(parameters) {
            $('.fancybox-outer').after('<a href="javascript:;" onclick="javascript:{parent.$.fancybox.close();}" class="fancybox-item fancybox-close" title="Close"></a>');
        }
    });
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th>Código Material</th>
            <th>Nombre Material</th>
            <th>Codigo Linea</th>
            <th>Stock</th>
            <th>Imagen</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
    </tfoot>
    <tbody>
        <?php
        while ($reg = mysql_fetch_array($lista_materiales)) {
            ?>
            <tr>
                <td><a href="carga_imagen_ma.php?codigo=<?php echo $reg['0'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['0'] ?></a></td>
                <td><a href="carga_imagen_ma.php?codigo=<?php echo $reg['0'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['1'] ?></a></td>
                <td><a href="carga_imagen_ma.php?codigo=<?php echo $reg['0'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['4'] ?></a></td>
                <?php
//                $sql_ingresos = "select sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id
//			where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000'
//			and id.cod_material='$reg[0]' and i.ingreso_anulado=0 and i.grupo_ingreso='2' ";
////                echo $sql_ingresos. "<br />";
//                $resp_ingresos = mysql_query($sql_ingresos);
//                $dat_ingresos = mysql_fetch_array($resp_ingresos);
//                $cant_ingresos = $dat_ingresos[0];
//                $sql_salidas = "select sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd
//			where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000'
//			and sd.cod_material='$reg[0]' and s.salida_anulada=0 and s.grupo_salida='2' ";
////                echo $sql_salidas. "<br />";
//                $resp_salidas = mysql_query($sql_salidas);
//                $dat_salidas = mysql_fetch_array($resp_salidas);
//                $cant_salidas = $dat_salidas[0];
//                $stock2 = $cant_ingresos - $cant_salidas;
                ?>
                <td><a href="carga_imagen_ma.php?codigo=<?php echo $reg['0'] ?>" data-fancybox-type="iframe" class="various"><?php echo "1"; ?></a></td>
                <td>
                    <?php if ($reg['2'] == ''): ?>
                        <a href="carga_imagen_ma.php?codigo=<?php echo $reg['0'] ?>" data-fancybox-type="iframe" class="various"><img src="lib/assets/img/prohibido-queda-1.png" alt="No hay Imagen" style="width: 85px" /></a>
                    <?php else: ?>
                        <a class="fancybox" href="lib/assets/uploads/<?php echo $reg['2'] ?>"><img src="lib/assets/uploads/<?php echo $reg['2'] ?> " alt="<?php echo $reg['2'] ?>" style="width: 85px" /></a> 
                        <?php endif; ?>
                </td>
            </tr>
            <?php
        }
        ?>
    <tbody>
</table>