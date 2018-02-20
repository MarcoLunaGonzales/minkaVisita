<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="iso-8859-1">
    <title>Adicionar Producto M&eacute;dicos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" language="javascript" src="lib/quitar_producto_medico.js"></script>
    <link type="text/css" href="css/tables.css" rel="stylesheet" />
    <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
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
    });
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
    </style>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php require_once('estilos3.inc'); ?>
            <br />
            <h3>Quitar Productos M&eacute;dico</h3>
        </header>
        <article id="contenido"></article>
    </div>
    <div class="modal"></div>
</body>
</html>