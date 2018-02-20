<?php
error_reporting(0);
require("conexion.inc");
$ciclo_gestion_inicio = $_GET['ciclo_inicio'];
$ciclo_gestion_destino = $_GET['ciclo_destino'];
$territorios = $_GET['territorios'];

$territorios_explode = explode(",", $territorios);

$ciclo_gestion_inicio_explode = explode("-", $ciclo_gestion_inicio);
$ciclo_inicio = $ciclo_gestion_inicio_explode[0];
$gestion_inicio = $ciclo_gestion_inicio_explode[1];

$ciclo_gestion_destino_final = explode("-", $ciclo_gestion_destino);
$ciclo_destino = $ciclo_gestion_destino_final[0];
$gestion_destino = $ciclo_gestion_destino_final[1];

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
    table tr th {
        padding: 0 10px    
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte cantidades x Territorio</h3>
        </header>
        <div class="row">
            <?php $sql_lista_productos = mysql_query("SELECT  DISTINCT bmd.cod_muestra, CONCAT(m.descripcion,' ',m.presentacion) as producto from banco_muestras bm, banco_muestras_detalle bmd, muestras_medicas m where bm.id = bmd.id and bmd.cod_muestra = m.codigo and bm.ciclo_final >= $ciclo_inicio and bm.ciclo_inicio <= $ciclo_destino and bm.gestion = $gestion_destino order by 2 ASC "); ?>
            <table border="1">
                <tr>
                    <th>Producto</th>
                    <?php foreach ($territorios_explode as $regional ) { ?>
                        <th><?php echo (mysql_result(mysql_query("SELECT descripcion from ciudades where cod_ciudad = $regional"),0,0)); ?></th>    
                    <?php } ?>
                    <th>Totales</th>
                </tr>
                <?php while($row_productos = mysql_fetch_array($sql_lista_productos)){ ?>
                    <?php $cantidad_total = 0; ?>
                    <tr>
                        <td><?php echo $row_productos[1]; ?></td>
                        <?php foreach ($territorios_explode as $regional ) { ?>
                        <td style="text-align:center">
                            <?php 
							$txtCantidad="SELECT SUM(bmd.cantidad) from banco_muestras bm, banco_muestras_detalle bmd, 
							medicos m where bm.id = bmd.id and bm.cod_med = m.cod_med and bmd.cod_muestra = '$row_productos[0]' 
							and bm.ciclo_final >= $ciclo_inicio and bm.ciclo_inicio <= $ciclo_destino and 
							bm.gestion = $gestion_destino and m.cod_ciudad = $regional and bm.estado=1";
							$sql_cantidad = mysql_query($txtCantidad); ?>
                            <?php if(mysql_result($sql_cantidad, 0, 0) == ''){echo '-';}else{echo "<span style='text-align: center; font-weight:bold'>".mysql_result($sql_cantidad, 0, 0)."</span>";}; ?>
                            <?php $cantidad_total = $cantidad_total + mysql_result($sql_cantidad, 0, 0); ?>
                        </td>
                        <?php } ?>
                        <th style="color:#000 !important"><?php echo $cantidad_total; ?></th>    
                    </tr>
                <?php } ?>
            </table>
        </div>
    </div>
</body>
</html>