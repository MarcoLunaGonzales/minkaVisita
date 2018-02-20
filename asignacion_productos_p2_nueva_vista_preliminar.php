<?php
error_reporting(0);
require("conexion.inc");
$territorios = $_GET['territorios'];
$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo = $ciclo_gestion_explode[0];
$gestionn = $ciclo_gestion_explode[1];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Productos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
    });
    </script>
    <style type="text/css">
    h4 {
        text-align: left
    }
    table tr th {
        padding: 5px 10px
    }
    #boton {
        position: fixed;
        right: 10px;
        top: 50px;
        background: #fff;
        border: 1px solid activeborder;
        padding: 2px;
        border-radius: 5px 5px;
    }
    #boton:hover {
        zoom: 1;
        filter: alpha(opacity=60);
        opacity: 0.6;
        background: #29677e;
        border: 1px solid activeborder;
        cursor: pointer;
        color: white !important;
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignaci&oacute;n de Productos Vista Preliminar</h3>
        </header>
        <?php 
        $sql_territorios = mysql_query("SELECT cod_ciudad,descripcion FROM ciudades where cod_ciudad in ($territorios) order by 1");
        while ($row_ciudades = mysql_fetch_array($sql_territorios)) {
            ?>
            <div class="row">
                <div class="twelve columns">
                    <h4 style="font-size:1.2em"><?php echo $row_ciudades[1]; ?></h4>
                </div>
                <?php  
                $sql_linea_asignada = mysql_query("SELECT DISTINCT a.linea from asignacion_de_porducto_detalle a, asignacion_de_prodcutos ac where a.id = ac.id and ac.ciudad = $row_ciudades[0] and ac.gestion = $gestionn and ac.ciclo = $ciclo");
                while ($row_linea_asignada = mysql_fetch_array($sql_linea_asignada)) {
                    $sql_plan_lineas = mysql_query("SELECT pd.especialidad, pd.linea, lv.nom_orden, pd.de FROM plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv WHERE pc.id = p.id_cab AND p.id = pd.id AND pd.linea = lv.codigo_l_visita AND pc.estado = 1 AND p.ciudad = $row_ciudades[0] and pd.linea = $row_linea_asignada[0] ORDER BY nom_orden");
                    $especialidad = mysql_result($sql_plan_lineas, 0, 0);
                    if($especialidad == ''){$especialidad = 'No hay plan de lineas para la regional.';}
                    $de = mysql_result($sql_plan_lineas, 0, 3);
                    if($de == ''){$de = '';}else{$de = " L".$de." de ";}
                    $sql_max_plan_lineas = mysql_query("SELECT MAX(pd.de) FROM plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv WHERE pc.id = p.id_cab AND p.id = pd.id AND pd.linea = lv.codigo_l_visita AND pc.estado = 1 AND p.ciudad = $row_ciudades[0] AND pd.especialidad = '$especialidad' ORDER BY nom_orden");
                    $max_plan_lineas = mysql_result($sql_max_plan_lineas, 0, 0);
                    // if($max_plan_lineas = ''){$max_plan_lineas = '';}
                    ?>
                    <div class="four columns end">
                        <h5><?php echo $especialidad.$de ?>  <?php echo $max_plan_lineas; ?></h5>
                        <?php  
                            $sql_productos = mysql_query("SELECT a.posicion, a.producto, CONCAT(m.descripcion,' ',m.presentacion) as producto_nom, a.cantidad from asignacion_de_porducto_detalle a, asignacion_de_prodcutos ac, muestras_medicas m where a.id = ac.id and m.codigo = a.producto and ac.ciudad = $row_ciudades[0] and ac.gestion = $gestionn and ac.ciclo = $ciclo and a.linea = $row_linea_asignada[0] ORDER BY 1 ASC");
                        ?>
                        <table border="1">
                            <tr>
                                <th>Posicion</th>
                                <th>Producto</th>
                                <th>Cantidad</th>
                            </tr>
                            <?php while ($row_productos = mysql_fetch_array($sql_productos)) { ?>
                            <tr>
                                <td align="center" style="text-align:center; font-weight:bold"><?php echo $row_productos[0]; ?></td>
                                <td><?php echo $row_productos[2]; ?></td>
                                <td><?php echo $row_productos[3]; ?></td>
                            </tr>
                            <?php } ?>
                        </table>
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </div>
            <div class="modal"></div>
        </body>
        </html>