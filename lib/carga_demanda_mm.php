<?php
require_once ('../conexion.inc');
header("Content-Type: text/html; charset=UTF-8");
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");
$lista_materiales = mysql_query("select * from demanda_mm ORDER BY nombre ASC");
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
            'afterClose': function () {
                //                window.location.reload();
//                alert($(this).attr('class'))
                
            }
        });
    });
</script>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th>Codigo</th>
            <th>Nombre </th>
            <th>Fecha Generacion</th>
            <th>Existencias A Fecha</th>
            <th>detalle</th>
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
                <td><a href="demanda_mm_detalle_mm.php?codigo=<?php echo $reg['0'] ?>&nombre=<?php echo $reg['1'] ?>&fecha=<?php echo $reg['3'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['0'] ?></a></td>
                <td><a href="demanda_mm_detalle_mm.php?codigo=<?php echo $reg['0'] ?>&nombre=<?php echo $reg['1'] ?>&fecha=<?php echo $reg['3'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['1'] ?></a></td>
                <td><a href="demanda_mm_detalle_mm.php?codigo=<?php echo $reg['0'] ?>&nombre=<?php echo $reg['1'] ?>&fecha=<?php echo $reg['3'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['2'] ?></a></td>
                <td><a href="demanda_mm_detalle_mm.php?codigo=<?php echo $reg['0'] ?>&nombre=<?php echo $reg['1'] ?>&fecha=<?php echo $reg['3'] ?>" data-fancybox-type="iframe" class="various"><?php echo $reg['3'] ; ?></a></td>
                <td><a href="demanda_mm_detalle_mm.php?codigo=<?php echo $reg['0'] ?>&nombre=<?php echo $reg['1'] ?>&fecha=<?php echo $reg['3'] ?>" data-fancybox-type="iframe" class="various">Detalle</a></td>
            </tr>
            <?php
        }
        ?>
    <tbody>
</table>