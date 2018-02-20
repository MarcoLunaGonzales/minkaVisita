<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Cargar Excel Asignacion De Productos</title>
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

        $("#continuar").click(function(){
            var archivo = $("#image-list-value").html()
            var ciclo_gestion = $("#ciclo").val();
            $.getJSON("parrilla/cargar.php",{
                "archivo" : archivo,
                "ciclo_gestion": ciclo_gestion
            },response);

            return false;
        })

        $("#vista_previa").click(function(){
            var windowSizeArray = [ "scrollbars=yes" ];
            var ciclo_gestion;
            ciclo_gestion = $(this).attr('ref');
            var url = $(this).attr("rel")+"?ciclo_gestion="+ciclo_gestion;
            var windowName = "popUp";//$(this).attr("name");
            var windowSize = windowSizeArray[0];
            window.open(url, windowName, windowSize);
        })
        
        function response(datos){
            var ciclo_gestion = $("#ciclo").val();
            if(datos.mensaje == 'lleno'){
                $("#faltantes").html(datos.cadena_mal)
            }
            if(datos.mensaje == 'vacio'){
                $("#faltantes").html(datos.cadena_bien)
                $("#vista_previa").css('display','inline-block')
                $("#vista_previa").attr('ref',ciclo_gestion)
            }
        }

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
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Cargar Excel Asignaci&oacute;n de Productos</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <table border="1">
                            <tr>
                                <th>Ciclo - Gestion Destino</th>
                                <td>
                                    <?php $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g 
                                    where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15") ?>
                                    <select name="ciclo" id="ciclo">
                                        <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                        <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Archivo para Subir</th>
                                <td>
                                    <form method="post" enctype="multipart/form-data"  action="lib/upload/upload.php">  
                                        <input type="file" name="images" id="images"  />  
                                        <button type="submit" id="btn" class="button">Cargar Excel!</button>  
                                    </form> 
                                </td>
                            </tr>
                        </table> 
                        <div id="response"></div>  
                        <ul id="image-list">  

                        </ul>
                        <div id="faltantes"></div>
                        <a href="javascript:void(0)" class="button" id="continuar">Continuar</a>
                        <a href="javascript:void(0)" rel="asignacion_prodcutos_vista_previa.php" class="button" id="vista_previa">Vista Previa</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
    <script type="text/javascript" src="lib/upload-parrilla/upload.js"></script>
</body>
</html>