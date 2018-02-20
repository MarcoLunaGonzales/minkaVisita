<?php
error_reporting(0);
require("conexion.inc");
$linea = $_GET['linea'];
$namee = $_GET['namee'];
$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$namee_explode = substr($namee, 0, -1);
$namee_explode = explode(",", $namee_explode);
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
                    <?php foreach ($namee_explode as $linea_final) { ?>
                    <div class="six columns end">
                        <h2 style="font-size: 1.2em; text-align:left"><?php echo $linea_final; ?></h2>
                        <table border="1">
                            <tr>
                                <th>Nom. MM</th>
                                <th>Nom. MA</th>
                                <th>Cant A</th>
                                <th>Cant B</th>
                                <th>Cant C</th>
                            </tr>
                            <?php $sql_items = mysql_query("SELECT DISTINCT  a.codigo_mm, ad.codigo_ma, ad.dist_a, ad.dist_b, ad.dist_c from asignacion_ma_excel a, asignacion_ma_excel_detalle ad , muestras_medicas m where a.id = ad.id_asignacion_ma and m.codigo = a.codigo_mm and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.espe_linea = '$linea_final' "); ?>
                            <?php if(mysql_num_rows($sql_items) == '0'){ ?>
                                <tr>
                                    <td colspan="5">No hay registros de Material de Apoyo para esta l&iacute;nea.</td>
                                </tr>
                            <?php }else{ ?>

                            
                                <?php while($row_items = mysql_fetch_array($sql_items)){ ?>
                                <tr>
                                    <td>
                                        <?php 
                                            $sql_nom_mm = mysql_query("SELECT CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$row_items[0]'");
                                            echo mysql_result($sql_nom_mm, 0,0);
                                        ?>
                                    </td>
                                    <td>
                                        <?php 
                                            $sql_nom_ma = mysql_query("SELECT descripcion_material from material_apoyo where codigo_material = '$row_items[1]'");
                                            echo mysql_result($sql_nom_ma, 0,0);
                                        ?>
                                    </td>
                                    <td><?php echo $row_items[2]; ?></td>
                                    <td><?php echo $row_items[3]; ?></td>
                                    <td><?php echo $row_items[4]; ?></td>
                                </tr>
                                <?php } ?>
                            <?php } ?>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        <!-- </div> -->
    </div>
    <div class="modal"></div>
</body>
</html>