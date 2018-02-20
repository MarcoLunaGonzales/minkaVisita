<?php
error_reporting(0);
require("conexion.inc");
$territorios = $_GET['territorios'];
$sql_territorios = mysql_query("SELECT descripcion FROM ciudades where cod_ciudad in ($territorios) order by 1");
$ciudades_finales = '';
while ($row_ciudades = mysql_fetch_array($sql_territorios)) {
    $ciudades_finales .= $row_ciudades[0].", "; 
}
$ciudades_finales = substr($ciudades_finales, 0, -2);
$date = date('Y-m-d');
$ciclo_gestion = $_GET['ciclo_gestion'] ;
$ciclo_gestion_enviar = "'".$ciclo_gestion."'";
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Productos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="css/acordeon.css" rel="stylesheet" />
    <link type="text/css" href="css/jquery-ui-1.8.9.custom.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/sticky.js"></script>
    <script type="text/javascript" src="lib/autocomplete/jquery-ui-1.8.9.custom.min.js"></script>
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
        var ciclo_gestion = '<?php echo $ciclo_gestion; ?>';
        var territorios = '<?php echo $territorios ?>';
        $("#busca_producto").autocomplete( { source: "js/tags/fake_json_endpoint2.php" });
        $('.acc_container').hide(); //Hide/close all containers
        $('.acc_trigger:first').addClass('active').next().show(); //Add "active" class to first trigger, then show/open the immediate next container
        //On Click
        $('.acc_trigger').click(function(){
            if( $(this).next().is(':hidden') ) { //If immediate next container is closed...
                $('.acc_trigger').removeClass('active').next().slideUp(); //Remove all .acc_trigger classes and slide up the immediate next container
                $(this).toggleClass('active').next().slideDown(); //Add .acc_trigger class to clicked trigger and slide down the immediate next container
            }
            return false; //Prevent the browser jump to the link anchor
        });
        $('.block h3').toggle(function(){
            $(this).parent().addClass("selected");
        },
        function () {
            $(this).parent().removeClass("selected");
        });
        $("#busca_producto").keypress(function(e){
            if(e.which == 13) {
                var variable = $(this).val();
                $("#ciudadess .var.selected").each(function(index){
                    $("div.caja_apoyo",this).append('<span class="espe">'+variable+'<img class="eliminar_espe" alt="Cerrar" src="imagenes/no.png"> </span>')
                }) 
                $(this).val("");
            }    
        })
        $(".eliminar_espe").live("click", function(){ 
            $(this).parent().remove();
        }); 
        $("#continuar").click(function(){
            var textos=''
            $(".var").each(function(index){
                var varr = $("input.cabeceras",this).val();
                $(".caja_apoyo .espe",this).each(function(index){
                    textos += varr+"@"+$(this).text()+",";
                })
                // textos = '';
            })
            // alert(textos)
            $.ajax({
                type: "POST",
                url: "ajax/asignacion_productos/guardar_asignacion.php",
                dataType : 'json',
                data: { 
                    cadena: textos,
                    ciclo: '<?php echo $ciclo_gestion ?>',
                    fecha: '<?php echo $date ?>',
                    territorios : '<?php echo $territorios ?>'
                }
            }).done(function() { 
                alert("Datos Guardados Satisfactoriamente.")
                // window.location.href = "asignacion_productos_p2_vista_preliminar.php?territorios="+<?php echo $territorios; ?>+"&ciclo_gestion="+<?php echo $ciclo_gestion ;?>;
                window.location.href = "asignacion_productos_p2_vista_preliminar.php?territorios="+territorios+"&ciclo_gestion="+ciclo_gestion;
            });
        })
    });
</script>
<style type="text/css">
.caja_apoyo {
    border: 1px solid #999999;
    padding: 10px;
    background: #cfcfcf;
    min-height:125px;height:auto!important;height:125px;
    width: 100%;
    overflow: hidden;
}  
input[type="text"], textarea {
    box-shadow: none !important
} 
#sticky_navigation_wrapper { wwidth:100%; height:70px; pposition: relative; z-index: 99; margin-top: 10px }
#sticky_navigation { width:100%; height:70px; background:url(images/trans-black-60.png); -moz-box-shadow: 0 0 5px #999; -webkit-box-shadow: 0 0 5px #999; box-shadow: 0 0 5px #999; }
#sticky_navigation input {
    margin-top: 12px;
    margin-left: 50px;
    float: left;
    height: 40px;
    width: 70%
}
#continuar {
    float: right;
    margin: 13px 40px;
}
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
</style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de Productos</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Ciudades: <?php echo $ciudades_finales; ?></h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <div id="sticky_navigation_wrapper" class="twelve columns">
                        <div id="sticky_navigation">
                            <div class="demo_container">
                                <input type="text" class="busca_producto" id="busca_producto" placeholder="Ingrese el nombre del producto. Para seleccionarlo pulse enter." />                                
                                <a class="button" href="javascript:void(0)" id="continuar">Guardar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row" id="ciudadess">
                <div class="twelve columns" style="padding-top: 30px">
                    <?php 
                    $sql_territorios2 = mysql_query("SELECT cod_ciudad,descripcion FROM ciudades where cod_ciudad in ($territorios) order by 1");
                    // echo("SELECT cod_ciudad,descripcion FROM ciudades where cod_ciudad in ($territorios) order by 1");
                    while ($row_ciudades2 = mysql_fetch_array($sql_territorios2)) {
                        ?>
                        <h2 class="acc_trigger"><a href="#"><?php echo $row_ciudades2[1]; ?></a></h2>
                        <div class="acc_container">
                            <div class="block">
                                <?php
                                $sql_lineas = mysql_query("SELECT pd.especialidad, pd.linea, lv.nom_orden, pd.de from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv 
                                    where pc.id = p.id_cab and p.id = pd.id and pd.linea = lv.codigo_l_visita and pc.estado = 1 and p.ciudad = $row_ciudades2[0] ORDER BY nom_orden");
                                // echo("SELECT pd.especialidad, pd.linea, lv.nom_orden, pd.de from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv 
                                    // where pc.id = p.id_cab and p.id = pd.id and pd.linea = lv.codigo_l_visita and pc.estado = 1 and p.ciudad = $row_ciudades2[0] ORDER BY nom_orden");
                                while ($row_lineas = mysql_fetch_array($sql_lineas)) {
                                    ?>
                                    <?php 
                                    $sql_max_linea = mysql_query("SELECT MAX(pd.de) from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv where pc.id = p.id_cab and p.id = pd.id and pd.linea = lv.codigo_l_visita and pc.estado = 1 and p.ciudad = $row_ciudades2[0] and pd.especialidad = '$row_lineas[0]' ORDER BY nom_orden");
                                    $de_max = mysql_result($sql_max_linea, 0, 0);
                                    ?>
                                    <div class="var">
                                        <h3><?php echo $row_lineas[0]; ?> L<?php echo $row_lineas[3]; ?> - <?php echo $de_max; ?></h3>
                                        <input type="hidden" value="<?php echo $row_ciudades2[0]."@".$row_lineas[1]; ?>" class="cabeceras" />
                                        <div class="caja_apoyo" id="" ></div>
                                    </div>
                                    <?php    
                                }
                                ?>
                            </div>
                        </div>
                        <?php  
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>