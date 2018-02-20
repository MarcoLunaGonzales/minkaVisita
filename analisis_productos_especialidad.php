<?php
error_reporting(0);
require("conexion.inc");

$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$productos_lista = $_GET['productos'];
$productos_lista_explode = explode(",", $productos_lista);

$nombres_productos = $_GET['codigos_productos'];
$nombres_productos = substr($nombres_productos, 0, -2);
$nombres_productos_explode = explode(",", $nombres_productos);

$productos_combinado = array_combine($productos_lista_explode, $nombres_productos_explode);
$count = 1;
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Asignacion de Material De Apoyo detalle</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript">
            jQuery(document).ready(function($) {

            });
        </script>
        <style>
            table.aa tr th, table.aa tr td {
                padding: 5px 15px
            }
        </style>
    </head>
    <body>
        <div id="container">
            <?php require("estilos3.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">ANALISIS DE PRODUCTOS X ESPECIALIDAD EN PARRILLA</h3>
                <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Productos: <?php echo $nombres_productos; ?></h3>
            </header>
            <div id="contenido">
                <div class="row">
                    <div class="seven columns centered">
                        <table border="1" class="aa">
                            <tr>
                                <th>&nbsp;</th>
                                <th>Producto</th>
                                <th>&nbsp;</th>
                            </tr>
                            <?php foreach($productos_combinado as $codigo_prod => $nom_prod){ ?>
                            <?php 
                                $sql_lineas = mysql_query("SELECT DISTINCT CONCAT(ad.especialidad, ' ', ad.linea)as nombre, ad.especialidad, ad.linea from asignacion_productos_excel a, asignacion_productos_excel_detalle ad where ad.id = a.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.producto = '$codigo_prod' order by 1");
                            ?>
                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $nom_prod; ?></td>
                                <td>
                                    <table border="1">
                                        <tr>
                                            <th>Especialidad</th>
                                            <th>L&iacute;nea</th>
                                            <th>Posici&oacute;n</th>
                                        </tr>
                                        <?php while($row_lineas = mysql_fetch_array($sql_lineas)){ ?>        
                                            <tr>
                                                <td><?php echo $row_lineas[1]; ?></td>
                                                <td><?php echo $row_lineas[2]; ?></td>
                                                <td>1</td>
                                            </tr>
                                        <?php } ?>
                                    </table>
                                </td>
                            </tr>
                            <?php $count++; ?>
                            <?php } ?>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal"></div>
    </body>
</html>