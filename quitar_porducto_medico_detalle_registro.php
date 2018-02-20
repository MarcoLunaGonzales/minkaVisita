<?php  
require_once ('conexion.inc');
error_reporting(0);

$cod_med = $_GET['cod_med'];
$linea   = $_GET['linea'];

$cod_linea = $linea;

$sql_nom_med = mysql_query("SELECT CONCAT(m.ap_pat_med,' ',m.ap_mat_med, ' ', m.nom_med) as nombre,  m.cod_ciudad from medicos m where m.cod_med = $cod_med ");
$nom_med     = mysql_result($sql_nom_med, 0, 0); 
$ciu_med     = mysql_result($sql_nom_med, 0, 1);

$sql_cod_ciu = mysql_query("SELECT cod_ciudad from medicos where cod_med = $cod_med");
$cod_ciudad  = mysql_result($sql_cod_ciu, 0, 0);

$sql_nom_lin = mysql_query("SELECT nombre_linea from lineas where codigo_linea = $linea");
$nom_linea   = mysql_result($sql_nom_lin, 0, 0);

$sql_espe_med = mysql_query("SELECT e.desc_especialidad, cl.categoria_med, e.cod_especialidad from especialidades e, categorias_lineas cl where cl.cod_especialidad = e.cod_especialidad and cl.cod_med = $cod_med and cl.codigo_linea = $linea");
$especialidad = mysql_result($sql_espe_med, 0, 0);
$categoria    = mysql_result($sql_espe_med, 0, 1);
$cod_espec    = mysql_result($sql_espe_med, 0, 2);
?>
<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="iso-8859-1">
    <title>Quitar Producto M&eacute;dicos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <link type="text/css" href="js/fancybox/jquery.fancybox.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.pack.js"></script>
    <script type="text/javascript" src="js/fancybox/jquery.fancybox.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $("body").on({
                ajaxStart: function() { 
                    $(this).addClass("loading"); 
                },
                ajaxStop: function() { 
                    $(this).removeClass("loading"); 
                }    
            });
            $("#ver_parrilla").fancybox({
                fitToView   : true,
                modal       : false
            });
            $(".quitar").click(function(event) {
                var cadena='';
                $(".aa .checkbox:checked").each(function(){
                    cadena += $(this).val()+",";
                })
                if(cadena == '' || cadena == 'undefined'){
                    alert("Seleccione por lo menos un producto")
                }else{

                    $.ajax({
                        type: "POST",
                        url: "ajax/adicionar_producto/quitar_producto.php",
                        dataType : 'json',
                        data: { 
                            cadena : cadena,
                            medico : '<?php echo $cod_med; ?>',
                            linea  : '<?php echo $cod_linea; ?>'
                        }
                    }).done(function(data) {
                        alert(data.mensaje)
                        window.location.href = "quitar_producto_medico_detalle.php?cod_med=<?php echo $cod_med?>&linea=<?php echo $cod_linea ?>";
                    });

                }
            });
});
function toggleChecked(status) {
    $(".checkbox").each( function() {
        $(this).attr("checked",status);
    })
}
</script>
<style>
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 0, 0, 0,.8 ) 
        url('http://i.stack.imgur.com/FhHRx.gif') 
        50% 50% 
        no-repeat;
    }
    body.loading {
        overflow: hidden;   
    }
    body.loading .modal {
        display: block;
    }
    a:hover {
        text-decoration: underline;
    }
    table.aa tr td {
        padding: 10px !important;
    }
    table.aa tr th {
        padding: 10px !important;
    }
    input[type="text"], textarea {
        box-shadow: none !important;
        border-radius: 0px !important;
        height: 38px !important;
        margin: 0 !important;
    }
</style>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php 
            require_once('estilos3.inc'); 
            ?>
            <br />
            <h3>Quitar Productos</h3>
            <h4 style="font-size: 1.1em">M&eacute;dico: <?php echo $nom_med; ?> | L&iacute;nea: <?php echo $nom_linea; ?></h4>
            <h4 style="font-size: 1em">Especialidad: <?php echo $especialidad; ?> | Categor&iacute;a: <?php echo $categoria; ?></h4>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="four columns left">
                    <input type="checkbox" onclick="toggleChecked(this.checked)"> Seleccionar TODO
                </div>
                <div class="two columns right">
                    <a href="#mostrar_parrilla" class="button" id="ver_parrilla">Ver parrilla Actual</a>
                </div>
            </div>
            <div class="row centered">
                <center>
                    <table border="1" class="aa">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Producto</th>
                        </tr>
                        <?php  
                        $sql_prod = mysql_query("SELECT DISTINCT pe.codigo_mm, CONCAT(m.descripcion,' ',m.presentacion) from producto_especialidad pe, muestras_medicas m where pe.codigo_mm = m.codigo and pe.cod_especialidad = '$cod_espec' and pe.codigo_linea = $cod_linea and pe.codigo_mm not in (SELECT mn.codigo_muestra from muestras_negadas mn where mn.codigo_muestra = pe.codigo_mm and mn.cod_med = $cod_med and mn.codigo_linea = $cod_linea) ORDER BY 2");
                        $num_prod = mysql_num_rows($sql_prod);
                        if($num_prod == 0){
                            ?>
                            <tr>
                                <td colspan="2">No existen productos en parrilla</td>
                            </tr>
                            <?php
                        }else{
                            while ( $row_prod = mysql_fetch_array($sql_prod)) {
                                ?>
                                <tr>
                                    <td>
                                        <input type='checkbox' name='codigo' value='<?php echo $row_prod[0]; ?>' class="checkbox">
                                    </td>
                                    <td><?php echo $row_prod[1]; ?></td>
                                </tr>
                                <?php
                            }
                        }
                        ?>
                    </table>
                </center>
            </div>
            <div class="row" style="margin-top: 20px">
                <div class="three columns centered">
                    <a href="javascript:void(0)" class="button quitar">Quitar Producto</a>
                </div>
            </div>
        </div>
    </div>
    <div id="mostrar_parrilla" style="width:100%;display: none;">
        <h3>Parrilla para el M&eacute;dico</h3>
        <div class="row">
            <div class="twleve columns">
                <?php  
                $sql_parrillas = mysql_query("SELECT DISTINCT lv.nom_orden, p.numero_visita, CONCAT(m.descripcion,' ',m.presentacion), pd.cantidad_muestra, ma.descripcion_material, pd.cantidad_material from parrilla p, parrilla_detalle pd, lineas_visita lv, muestras_medicas m, material_apoyo ma where ma.codigo_material = pd.codigo_material and p.codigo_parrilla = pd.codigo_parrilla and lv.codigo_l_visita = p.codigo_l_visita and pd.codigo_muestra = m.codigo and p.cod_ciclo = $ciclo_global and p.codigo_gestion = $codigo_gestion and p.codigo_linea = $cod_linea and p.agencia = $ciu_med and p.cod_especialidad = '$cod_espec' and p.categoria_med = '$categoria' order by 1, 2");
                $aux  = '';
                $aux2 = '';
                ?>
                <table>
                    <?php 
                    while ($row_parrillas = mysql_fetch_array($sql_parrillas)) {
                        if($aux != $row_parrillas[0] or $aux2 != $row_parrillas[1]){
                            $count = 1;
                            ?>
                            <tr>
                                <th>&nbsp;</th>
                                <th>L&iacute;nea</th>
                                <th>N&uacute;mero Visita</th>
                                <th>Muestra M&eacute;dica</th>
                                <th>Cantidad</th>
                                <th>Material Apoyo</th>
                                <th>Cantidad</th>
                            </tr>
                            <?php  
                        }
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>  
                            <td><?php echo $row_parrillas[0]; ?></td>
                            <td><?php echo $row_parrillas[1]; ?></td>
                            <td><?php echo $row_parrillas[2]; ?></td>
                            <td><?php echo $row_parrillas[3]; ?></td>
                            <td><?php echo $row_parrillas[4]; ?></td>
                            <td><?php echo $row_parrillas[5]; ?></td>
                        </tr>
                        <?php
                        $count++;
                        $aux  = $row_parrillas[0];
                        $aux2 = $row_parrillas[1];
                    }
                    ?>
                </table>
            </div>
        </div>
        <div class="row centered">
            <div class="three columns centered">
                <a href="javascript:$.fancybox.close();" class="button">Cerrar</a>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>