<?php
require_once ('../conexion.inc');
error_reporting(0);
$sql_gestion = mysql_query("Select max(codigo_gestion) as gestion from rutero_maestro_cab_aprobado");
while ($row_gestion = mysql_fetch_assoc($sql_gestion)) {
    $codGestion = $row_gestion['gestion'];
}

$sql_llenados = mysql_query("select cod_med from banco_muestras");
while ($row_bm = mysql_fetch_array($sql_llenados)) {
    $bm_codigos .= $row_bm[0] . ",";
}

$bm_codigos_finales = substr($bm_codigos, 0, -1);

$sql_medico_lista = mysql_query(" SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med, bm.id from rutero_maestro_cab_aprobado rc , rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, medicos m ,banco_muestras bm where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_gestion = $codGestion and rc.estado_aprobado = 1 and rd.cod_med = m.cod_med and bm.cod_med = m.cod_med and m.cod_ciudad = $global_agencia ORDER BY 1 ");
//        and rd.cod_med = m.cod_med and m.cod_med in ($bm_codigos_finales) and rc.codigo_linea = $global_linea ORDER BY 1 ");

// echo(" SELECT DISTINCT m.cod_med, rd.cod_especialidad, rd.categoria_med, bm.id from rutero_maestro_cab_aprobado rc , rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd, medicos m ,banco_muestras bm where rc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rd.cod_contacto and rc.codigo_gestion = $codGestion and rc.estado_aprobado = 1 and rd.cod_med = m.cod_med and bm.cod_med = m.cod_med and m.cod_ciudad = $global_agencia ORDER BY 1 ");

$count = 1;
?>
<script type="text/javascript" language="javascript" src="lib/listado_materiales_baco3.js"></script>
<script type="text/javascript" src="js/fancybox/jquery.mousewheel-3.0.6.pack.js"></script>
<link rel="stylesheet" href="js/fancybox/jquery.fancybox.css?v=2.1.3" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js?v=2.1.3"></script>
<link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-buttons.css?v=1.0.5" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-buttons.js?v=1.0.5"></script>
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-media.js?v=1.0.5"></script>

<link rel="stylesheet" href="js/fancybox/helpers/jquery.fancybox-thumbs.css?v=1.0.7" type="text/css" media="screen" />
<script type="text/javascript" src="js/fancybox/helpers/jquery.fancybox-thumbs.js?v=1.0.7"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $(".various").fancybox({
            fitToView	: true,
            width : '90%',
            height :'90%',
            autoSize	: false,
            closeClick	: false,
            openEffect	: 'none',
            closeEffect	: 'none',
            hideOnContentClick: false
        });
        $("#aprobar").click(function(){
            var checked = "";
            $('#tabla_listado_materiales input[type=checkbox]:checked').each(function(){
                checked +=$(this).val()+",";
            });
            window.location = "carga_medicos_calculo_vp.php?id="+checked;
        })
        $("#rechazar").click(function(){
            var checked = "";
            $('#tabla_listado_materiales input[type=checkbox]:checked').each(function(){
                checked +=$(this).val()+",";
            });
            $.getJSON("ajax/banco_muestras/rechazar.php",{
                "medicos": checked
            },response);
    
            return false;
        })
    })
    function response(datos){
        alert(datos)
        window.location = "calculo_banco_muestras.php";
    }
</script>
<style type="text/css">
    .aprobar_button{
        float: right;
        background-color: #DDDDDD;
        padding: 2px 5px;
        border: 1px solid #AAAAAA;
    }
    .aprobar_button:hover {
        zoom: 1;
        filter: alpha(opacity=70);
        opacity: 0.7;
    }
    .aprobar_button a {
        text-decoration: none !important;
        color: #084B8A;
        font-weight: bold;
    }
</style>

<table cellpadding="0" cellspacing="0" border="0" class="display" id="tabla_listado_materiales">
    <thead>
        <tr>
            <th scope="col">Nro.Cont.</th>
            <th scope="col">Ciudad</th>
            <th scope="col">M&eacute;dico</th>
            <th scope="col">Especialidad</th>
            <th scope="col">Categor&iacute;a</th>
            <th scope="col">Detallado</th>
            <th scope="col">Aprobar</th>
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
            $codigo_medico = $row['cod_med'];
            $idd = $row['id'];
            $sql_cantidad = mysql_query("select sum(cantidad) from banco_muestras_detalle where cod_med = $codigo_medico");
            $cantidad = mysql_result($sql_cantidad, 0, 0);
            $sql_datos_medico = "Select m.ap_pat_med, m.ap_mat_med, m.nom_med, c.descripcion from medicos m, ciudades c where cod_med = $codigo_medico and m.cod_ciudad = c.cod_ciudad order by ap_pat_med";
            $resp_sql_datos_medico = mysql_query($sql_datos_medico);
            while ($row_m = mysql_fetch_array($resp_sql_datos_medico)) {

//                $sql_aprobado = mysql_query("select * from banco_muestra_cantidad_visitador where cod_medico = $codigo_medico");
//                $num_aprobado = mysql_num_rows($sql_aprobado);
                $sql_aprobado = mysql_query("select estado from banco_muestras where cod_med = $codigo_medico");
                $num_aprobado = mysql_result($sql_aprobado, 0,0);
                ?>
                <?php // if ($num_aprobado <> 1): ?>
                <tr>
                    <td><?php echo $count ?></td>
                    <td><?php echo $row_m[3] ?></td>
                    <td><?php echo $row_m[0] ?> <?php echo $row_m[1] ?> <?php echo $row_m[2] ?></td>
                    <td style="text-align: center"><?php echo $row['cod_especialidad'] ?></td>
                    <td style="text-align: center"><?php echo $row['categoria_med'] ?></td>
                    <td style="text-align: center"><a href="lib/banco_muestras_detalle/data.php?codigo=<?php echo $idd ?>" class="various fancybox.ajax"><?php echo $cantidad; ?> productos</a></td>
                    <td style="text-align: center">
                        <?php if ($num_aprobado == 3): ?>
                            <p>Pre-Aprobado</p>
                        <?php endif; ?>
                        <?php if ($num_aprobado == 1): ?>
                            <p>Aprobado</p>
                        <?php endif; ?>
                        <?php if ($num_aprobado == 2): ?>
                            <p>Rechazado</p>
                        <?php endif; ?>
                        <?php if ($num_aprobado == 0): ?>
                            <input type="checkbox" value="<?php echo $idd; ?>" />
                        <?php endif; ?>
                            
                    </td>
                </tr>
                <?php // endif; ?>
                <?php
            }
            $count++;
        }
        ?>
    <tbody>
</table>
<div class="aprobar_button">
    <a href="javascript:void(0)" id="aprobar">Realizar Calculos M&eacute;dicos seleccionados</a>
</div><br />
<!-- <div class="aprobar_button" style="clear:both; margin-top: 10px">
    <a href="javascript:void(0)" id="rechazar">Rechazar M&eacute;dicos seleccionados</a>
</div> -->