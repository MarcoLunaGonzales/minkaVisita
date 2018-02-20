<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="lib/cal.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("body").on({
                    ajaxStart: function() { 
                        $(this).addClass("loading"); 
                    },
                    ajaxStop: function() { 
                        $(this).removeClass("loading"); 
                    }    
                });
                $('input.one').simpleDatepicker({ startdate: <?php echo $year ?>, enddate: 2020 });
                $("#enviar").click(function(){
                    var nombre,fecha;
                    nombre = $("#nombre").val();
                    fecha = $("#fecha1").val();
                    $.ajax({
                        type: "POST",
                        url: "ajax/demanda/generar.php",
                        dataType : 'json',
                        data: { 
                            nombre: nombre,
                            fecha: fecha
                        },
                        success : function(data){
                            alert(data.msg);
                            if (data.error === false){
                                window.location.href = "demanda_mm_detalle.php";
                            }else{
                                window.location.href = "demanda_mm.php";
                            }
                        }
                    })
                })                
            });
        </script>
        <style type="text/css">
            #contenido tr th {
                padding: 5px
            }
            .controls input, .controls select {
                padding: 0
            }
            input[type="button"] {
                margin: 10px 0;
                cursor: pointer;
                background: #fff;
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
    </head>
    <body>
        <div id="container">
            <?php require("estilos2.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Registro Demanda Muestras M&eacute;dicas</h3>
            </header>
            <div id="contenido">
                <center>
                    <table border="1">
                        <tr>
                            <th>Nombre:</th>
                            <td>
                                <input  type="text" value="" id="nombre" />
                            </td>
                        </tr>
                        <tr>
                            <th>A Fecha:</th>
                            <td><input class="one" type="text" name="date" value="" id="fecha1" /></td>
                        </tr>
                    </table>
                    <input type="button" id="enviar" value="Generar" />
                </center>
            </div>
        </div>
        <div class="modal"></div>
    </body>
</html>