<?php
error_reporting(0);
require("conexion.inc");
$ciclo_final = $_GET['ciclo'];
$gestion_final = $_GET['gestion'];
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de Material De Apoyo</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <a href="generar_parrilla_automatica.php?ciclo=<?php echo $ciclo_final ?>&gestion=<?php echo $gestion_final ?>" class="button" id="parrilla">Generar Parrilla</a>
                        <a href="asignacion_ma_vista_previap.php?ciclo=<?php echo $ciclo_final ?>&gestion=<?php echo $gestion_final ?>&territorio=123,102,114,113,122,104,119,120,121,116,124,109,118,117" class="button" id="vista_previa">Vista Previa</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>