<?php
error_reporting(0);
require("conexion.inc");
$territorio = $_GET['territorio'];
$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion de Material De Apoyo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
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
        $.ajax({
            type: "POST",
            url: "ajax/parrilla_excel/generar_parrilla_automatica_vista.php",
            dataType : 'json',
            data: { 
                ciclo      : <?php echo $ciclo_final; ?>,
                gestion    : <?php echo $gestion_final; ?>,
                territorio : '<?php echo $territorio; ?>'
            }
        }).done(function(data) {
            $("#tabal").html(data.cadena)
        });
    });
    </script>
    <style type="text/css">
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
    form, select {
        margin: 10px 0;
    }
    th {
        padding: 0 10px 
    }
    #overlay { 
        display:none; 
        position:absolute; 
        background:#fff; 
    }
    #img-load { 
        position:absolute; 
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Asignaci&oacute;n Material De apoyo</h3>
        </header>
        <!-- <div id="contenido"> -->
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <div id="tabal"></div>
                    </center>
                </div>
            </div>
        <!-- </div> -->
    </div>
    <div class="modal"></div>
</body>
</html>