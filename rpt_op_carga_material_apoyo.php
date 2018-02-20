<?php require("conexion.inc"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title></title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <style type="text/css">
    #header h2 { color: #5F7BA9; font-size: 11pt; font-family: Verdana}
    body { background: #fff url('imagenes/fondo_pagina.jpg') no-repeat right; }
    #filtro th { text-align: left; width: 30%; font-size: 12px }
    .button { padding: 0.4em 1em; cursor: pointer; text-align: center; background: url("images/ui-bg_glass_80_d7ebf9_1x400.png") repeat-x ; margin: 3px 5px 0; text-decoration: none !important; border: 1px solid #A1C2EB ; line-height: 30px; color: #000; -webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px; font-size: 12px; color: #000; width: 100px}
    .button:hover { opacity: 0.60 }
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

        $("#exportar").click(function(){
            var archivo = $("#image-list-value").html()
            $.getJSON("baco/export.php",{
                "archivo" : archivo
            },response);

            return false;
        })
        function response(datos){
            if(datos.mensaje == 'lleno'){
                $("#faltantes").html(datos.cadena)
            }
            if(datos.mensaje == 'vacio'){
                $("#faltantes").html(datos.cadena)
            }
        }
    })
    </script>
</head>
<body>
    <div id="container">
        <div id="header">
            <center>
                <h2>Cargar Reporte Ingreso Salida Material de Apoyo</h2>
            </center>
        </div>
        <div id="content">
            <center>
                <form method="post" enctype="multipart/form-data"  action="lib/upload/upload.php">  
                    <input type="file" name="images" id="images"  />  
                    <button type="submit" id="btn">Cargar Excel!</button>  
                </form>  
                <div id="response"></div>  
                <ul id="image-list">  

                </ul>  
            </center>
            <center>
                <a href="#" class="button" style="padding: 8px 16px" id="exportar">Exportar a Baco</a>
            </center>
            <center>
                <div id="faltantes">

                </div>
            </center>
        </div>
    </div>
    <div class="modal"></div>
    <script type="text/javascript" src="lib/upload/upload.js"></script>
</body>
</html>