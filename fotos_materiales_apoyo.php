<!DOCTYPE HTML>
<html lang="es-US">
    <head>
        <meta charset="utf-8">
        <title>Cargado Fotos Materiales Apoyo</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" language="javascript" src="lib/funciones_carga_imagen.js"></script>
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
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
            /* When the body has the loading class, we turn
               the scrollbar off with overflow:hidden */
            body.loading {
                overflow: hidden;   
            }

            /* Anytime the body has the loading class, our
               modal element will be visible */
            body.loading .modal {
                display: block;
            }
        </style>
        <script type="text/javascript">
            $(document).ready(function(){
                $("body").on({
                    ajaxStart: function() { 
                        $(this).addClass("loading"); 
                    },
                    ajaxStop: function() { 
                        $(this).removeClass("loading"); 
                    }    
                });
            })</script>
    </head>
    <body>
        <div id="container">
            <header id="titulo">
                <h3>Cargado Fotos Materiales Apoyo</h3>
            </header>
            <article id="contenido"></article>
            <div class="modal"></div>
        </div>
    </body>
</html>