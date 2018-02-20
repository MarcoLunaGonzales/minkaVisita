<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="js/tableDnD/tablednd.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="js/tableDnD/js/jquery.tablednd.0.7.min.js"></script>
        <style type="text/css">
            body {
                position: relative
            }
            h1{
                position: relative;
                left: 0;
                top: 0;
                text-align: left;
            }
            #sticky {
                position: fixed;
                right: -10%
            }
            .cajitas {
                width: 25%;
                padding: 10px ;
                margin: 5px 0
            }
            .cajitas.verde{
                background: green;
                color: #fff;
                font-weight: bold;
            }
            .cajitas.rojo{
                background: red;
                color: #fff;
                font-weight: bold;
            }
            .cajitas.amarillo{
                background: yellow;
                color: #000;
                font-weight: bold;
            }
            .cajitas.azul{
                background: blue;
                color: #fff;
                font-weight: bold;
            }
            table tbody tr:nth-child(2n) {
                background: #d6d6d6
            }
        </style>
        <script type="text/javascript">
            
            jQuery(document).ready(function() {
                $(".row_grandes").each(function(index){
                    jQuery('tbody.'+index+' .codigo_producto',this).each(function() {
                        var itemName = jQuery(this).text();
                        var match = jQuery('.'+index+' tr td.comparerow:contains("' + itemName + '")');
                        if(match.length){
                            jQuery(this).css({
                                'background':'green',
                                'color': 'white'
                            })
                        }else{
                            jQuery(this).css({
                                'background':'red',
                                'color': 'white'
                            })
                        }
                    })
                    jQuery('tbody.'+index+' .cantidad_producto',this).each(function() {
                        var itemName = jQuery(this).text();
                        var match = jQuery('.'+index+' tr td.comparecantidad:contains("' + itemName + '")');
                        if(match.length){
                            jQuery(this).css({
                                'background':'green',
                                'color': 'white'
                            })
                        }else{
                            jQuery(this).css({
                                'background':'yellow',
                                'color': 'black'
                            })
                        }
                    }); 
                })
                
                                
                /*FIN ANTIGUO*/
            });
        </script>
    </head>
    <body>
        <?php
//error_reporting(0);
        require("conexion.inc");
//        $territorio = 116;
//        $sql_visitadores = mysql_query("select DISTINCT cod_visitador from distribucion_productos_visitadores where territorio = $territorio order by 1");
//        $sql_visitadores = mysql_query("select DISTINCT cod_visitador from distribucion_productos_visitadores where cod_visitador in (1070,1190) order by 1");
//        $sql_visitadores = mysql_query("select DISTINCT cod_visitador from distribucion_productos_visitadores where cod_visitador in (1049,1054,1066) order by 1");
        $sql_visitadores = mysql_query("select DISTINCT cod_visitador from distribucion_productos_visitadores where codigo_linea = 1021 order by 1");
        while ($row_visitadores = mysql_fetch_array($sql_visitadores)) {
            $codigo_visitadores .= $row_visitadores[0] . ",";
        }
        $codigo_visitadores_sub = substr($codigo_visitadores, 0, -1);
        $codigo_visitadores_explode = explode(",", $codigo_visitadores_sub);
        ?>
    <center>
        <div id="sticky">
            <div class="cajitas verde">Todo Bien</div>
            <div class="cajitas rojo">No Esta en la Distribuci&oacute;n</div>
            <div class="cajitas amarillo"> Esta en la distribuci&oacute;n pero no tiene la misma cantidad</div>
            <!--<div class="cajitas azul">No esta en la distribucion Oficial (Extra)</div>-->
        </div>
    </center>
    <?php
    $contador = 0;
    foreach ($codigo_visitadores_explode as $codigo_final_visitador) {
        $sql_distribucion = mysql_query("select * from distribucion_productos_visitadores where codigo_gestion = 1009 and codigo_linea = 1021 and cod_ciclo = 10 and cod_visitador = $codigo_final_visitador  ORDER BY cod_visitador, codigo_producto");
        ?>
        <div style="width: 100%; margin: 10px 0; display: block; overflow: hidden;" class="row_grandes">
            <?php
            $sql_nom_visitador = mysql_query("select CONCAT(nombres,' ',paterno,' ',materno) from funcionarios  where codigo_funcionario = $codigo_final_visitador ");
            ?>
            <h1><?php echo mysql_result($sql_nom_visitador, 0, 0) . " " . $codigo_final_visitador ?></h1>
            <div style="float:left; width: 30%; margin-left: 5px">
                <h3>Local</h3>
                <table border="1"  style="float: left; margin-left: 5px" width="100%"  >
                    <thead>
                        <tr>
                            <th>Producto Distribucion</th>
                            <th>Nombre Producto</th>
                            <th>Can Planificada</th>
                        </tr>
                    </thead>
                    <tbody class="<?php echo $contador ?>">
                        <?php while ($row_distribuicion = mysql_fetch_array($sql_distribucion)) { ?>
                            <tr>
                                <td class="codigo_producto"><?php echo $row_distribuicion[5] ?></td>
                                <td >
                                    <?php
                                    $sql_nombre = mysql_query("select CONCAT(descripcion,' ',presentacion) from muestras_medicas where codigo = '$row_distribuicion[5]'");
                                    $num = mysql_num_rows($sql_nombre);
                                    if ($num > 0) {
                                        echo mysql_result($sql_nombre, 0, 0);
                                    } else {
                                        $sql_nombre2 = mysql_query("select descripcion_material from material_apoyo where codigo_material = '$row_distribuicion[5]'");
                                        echo mysql_result($sql_nombre2, 0, 0);
                                    }
                                    ?>
                                </td>
                                <td class="cantidad_producto"><?php echo $row_distribuicion[6] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>

            <div style="float:left; width: 30%; margin-left: 5px">
                <h3>Servidor</h3>
                <table border="1"  style="float: right" width="100%"  class="<?php echo $contador ?>">
                    <thead>
                        <tr>
                            <th>Producto Distribucion</th>
                            <th>Can Planificada</th>
                            <th>Can Distribuida</th>
                            <th>Can Sac almacen</th>
                        </tr>
                    </thead>
                    <?php $sql_distribucion_temp = mysql_query("select * from distribucion_productos_visitadores_temp where codigo_gestion = 1009 and codigo_linea = 1021 and cod_ciclo = 10 and cod_visitador = $codigo_final_visitador  ORDER BY cod_visitador, codigo_producto"); ?>
                    <tbody>
                        <?php while ($row_distribuicion_temp = mysql_fetch_array($sql_distribucion_temp)) { ?>
                            <tr>
                                <td class="comparerow"><?php echo $row_distribuicion_temp[5] ?></td>
                                <td class="comparecantidad"><?php echo $row_distribuicion_temp[6] ?></td>
                                <td><?php echo $row_distribuicion_temp[7] ?></td>
                                <td><?php echo $row_distribuicion_temp[9] ?></td>
                            </tr>
                            <?php $codigos_productos .= "'" . $row_distribuicion_temp[5] . "',"; ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div style="float:left; width: 30%">
                <h3>Servidor 21</h3>
                <table border="1"  style="float: left" width="100%" >
                    <thead>
                        <tr>
                            <th>Producto Distribucion</th>
                            <th>Can Planificada</th>
                            <th>Can Distribuida</th>
                            <th>Can Sac almacen</th>
                        </tr>
                    </thead>
                    <?php
                    $codigos_productos_sub = substr($codigos_productos, 0, -1);
                    ?>
                    <?php $sql_distribucion_temp_21 = mysql_query("select * from distribucion_productos_visitadores_temp_21 where codigo_gestion = 1009 and codigo_linea = 1021 and cod_ciclo = 10 and cod_visitador = $codigo_final_visitador and codigo_producto not in ($codigos_productos_sub)  ORDER BY cod_visitador, codigo_producto"); ?>
                    <tbody>
                        <?php while ($row_distribuicion_temp_21 = mysql_fetch_array($sql_distribucion_temp_21)) { ?>
                            <tr>
                                <td><?php echo $row_distribuicion_temp_21[5] ?></td>
                                <td><?php echo $row_distribuicion_temp_21[6] ?></td>
                                <td><?php echo $row_distribuicion_temp_21[7] ?></td>
                                <td><?php echo $row_distribuicion_temp_21[9] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php
        $contador++;
    }
    ?>
</body>
</html>