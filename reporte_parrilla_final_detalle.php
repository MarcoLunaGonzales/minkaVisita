<?php
error_reporting(0);
require("conexion.inc");
date_default_timezone_set('America/La_Paz');
$territorio = $_GET['territorio'];
$ciclo_gestion = $_GET['ciclo_gestion'];
$lineas = $_GET['linea'];

$sql_nom_territorio = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $territorio");
$nom_territorio = mysql_result($sql_nom_territorio, 0, 0);


// echo $territorio." - ".$ciclo_gestion." - ".$linea;
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$sql_count_lineas = mysql_query("SELECT COUNT(*) from lineas_visita_nom_generio where codigo_l_visita in ($lineas)");
$count_lineas = mysql_result($sql_count_lineas, 0, 0);
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
    #container th {
        padding: 5px 10px
    }
    .total {
        background: green;
        color: #fff
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Parrilla Realizada</h3>
            <h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada; text-transform: capitalize">Fecha: <?php echo date('Y-m-d H:i:s'); ?></h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; text-transform: capitalize">Territorio: <?php echo $nom_territorio; ?> </h3>
        </header>
        <!-- <div id="contenido"> -->
        <div class="row">
            <div class="twelve columns">
                <center>
                    <table border="1">
                        <tr>
                            <th>Especialidad / Producto</th>
                            <?php $sql_nom_lineas = mysql_query("SELECT * from lineas_visita_nom_generio where codigo_l_visita in ($lineas) ORDER BY 2") ?>
                            <?php while($row_nom_lineas = mysql_fetch_array($sql_nom_lineas)){ ?>
                            <th><?php echo $row_nom_lineas[1]; ?></th>
                            <?php $cadena_lineas .= $row_nom_lineas[0].",";?>
                            <?php } ?>
                        </tr>
                        <tr>
                            <th><?php echo ''; ?></th>
                            <?php $cadena_lineas = substr($cadena_lineas, 0, -1); ?>
                            <?php $cadena_lineas = explode(",",$cadena_lineas); ?>
                            <?php foreach($cadena_lineas as $linea_final){ ?>
                            <?php //for($aux = 1; $aux <= $count_lineas; $aux++){ ?>
                            <th>
                                <table>
                                    <tr>
                                        <th>A</th>
                                        <th>B</th>
                                        <th>C</th>
                                        <th class="total">Total</th>
                                    </tr>
                                </table>
                            </th>
                            <?php } ?>
                        </tr>
                        <?php $sql_productos = mysql_query("SELECT DISTINCT pd.codigo_muestra, CONCAT(m.descripcion,' ',m.presentacion) as nombre from parrilla p, parrilla_detalle pd, muestras_medicas m where p.codigo_parrilla = pd.codigo_parrilla and m.codigo = pd.codigo_muestra and p.agencia = $territorio and codigo_l_visita in ($lineas) and p.cod_ciclo= $ciclo_final and p.codigo_gestion = $gestion_final ORDER BY 2") ?>
                        <?php while($row_productos = mysql_fetch_array($sql_productos)){ ?>
                        <tr>
                            <th style="color:black; font-weight:normal; font-size: 12px"><?php echo $row_productos[1]."-".$row_productos[0]; ?></th>
                            <?php foreach($cadena_lineas as $linea_final){ ?>
                            <?php //for($aux = 1; $aux <= $count_lineas; $aux++){ ?>
                            <td>
                                <table border="1" style="margin:0">
                                    <tr>
                                        <td width="32px">
                                            <?php
                                                $sql_cantidad_a = mysql_query("SELECT  sum(pd.cantidad_muestra) from parrilla p, parrilla_detalle pd where p.codigo_parrilla = pd.codigo_parrilla and p.agencia = $territorio and codigo_l_visita = $linea_final and p.cod_ciclo= $ciclo_final and p.codigo_gestion = $gestion_final and pd.codigo_muestra = '$row_productos[0]' and p.categoria_med = 'A'");
                                                $num_a = mysql_result($sql_cantidad_a, 0, 0);
                                                if($num_a == '' || $num_a == null){$num_a = '-';}
                                                echo $num_a;
                                            ?>
                                        </td>
                                        <td width="32px">
                                            <?php
                                                $sql_cantidad_b = mysql_query("SELECT  sum(pd.cantidad_muestra) from parrilla p, parrilla_detalle pd where p.codigo_parrilla = pd.codigo_parrilla and p.agencia = $territorio and codigo_l_visita = $linea_final and p.cod_ciclo= $ciclo_final and p.codigo_gestion = $gestion_final and pd.codigo_muestra = '$row_productos[0]' and p.categoria_med = 'B'");
                                                $num_b = mysql_result($sql_cantidad_b, 0, 0);
                                                if($num_b == ''){$num_b = '-';}
                                                echo $num_b;
                                            ?>
                                        </td>
                                        <td width="38px">
                                            <?php
                                                $sql_cantidad_c = mysql_query("SELECT  sum(pd.cantidad_muestra) from parrilla p, parrilla_detalle pd where p.codigo_parrilla = pd.codigo_parrilla and p.agencia = $territorio and codigo_l_visita = $linea_final and p.cod_ciclo= $ciclo_final and p.codigo_gestion = $gestion_final and pd.codigo_muestra = '$row_productos[0]' and p.categoria_med = 'C'");
                                                $num_c = mysql_result($sql_cantidad_c, 0, 0);
                                                if($num_c == ''){$num_c = '-';}
                                                echo $num_c;
                                            ?>
                                        </td>
                                        <td  width="32px" class="total"><?php echo ($num_a + $num_b + $num_c); ?></td>
                                    </tr>
                                </table>
                            </td>
                            <?php } ?>
                        </tr>
                        <?php } ?>
                    </table>
                </center>
            </div>
        </div>
        <!-- </div> -->
    </div>
    <div class="modal"></div>
</body>
</html>