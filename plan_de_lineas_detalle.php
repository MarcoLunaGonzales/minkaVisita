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
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
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
                $(".cambio_estado").click(function(){
                    var id,estado;
                    id = $(this).attr('id_cab');
                    $.getJSON("ajax/plan_lineas/cambio_estado.php",{
                        "id" : id
                    },response);
    
                    return false;
                })
                function response(datos){
//                    alert(datos)
                    window.location.href = "plan_de_lineas_detalle.php";
                }
            });
        </script>
        <style type="text/css">
            table th {
                padding: 5px
            }
            a:hover {
                text-decoration: underline
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
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Plan de L&iacute;neas</h3>
            </header>
            <div id="contenido">
                <div class="row" style="width:100%" id="tab1">
                    <div class="twelve columns">
                        <table border="1">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Nombre</th>
                                <th>Fecha</th>
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Estado</th>
                            </tr>
                            <?php
                            $sql = mysql_query("select * from plan_linea_cab");
                            while ($row = mysql_fetch_array($sql)) {
                                ?>
                                <tr>
                                    <td><?php echo $row[0]; ?></td>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><a href="plan_de_lineas_editar.php?id=<?php echo $row[0] ?>" class="editar">Editar</a></td>
                                    <td><a href="plan_de_lineas_ver_detalle.php?id=<?php echo $row[0] ?>" class="ver_detalle">Ver detalle</a></td>
                                    <td>
                                        <?php
                                        if ($row[3] == 1) {
                                            ?>
                                            <a href="javascript:void(0)" id_cab="<?php echo $row[0]; ?>" class="cambio_estado"><img src="imagenes/si.png" alt="Activo" /></a>
                                            <?php
                                        } else {
                                            ?>
                                            <a href="javascript:void(0)" id_cab="<?php echo $row[0]; ?>" class="cambio_estado"><img src="imagenes/no.png" alt="Activo" /></a>
                                            <?php
                                        }
                                        ?>
                                    </td>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                        <a href="plan_de_lineas.php" class="button">Agregar Plan de Linea</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal"></div>
    </body>
</html>