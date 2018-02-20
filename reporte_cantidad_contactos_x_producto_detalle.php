<?php
error_reporting(0);
require("conexion.inc");
header ( "Content-Type: text/html; charset=UTF-8" );
mysql_query("SET character_set_results = 'utf8', character_set_client = 'utf8', character_set_connection = 'utf8', character_set_database = 'utf8', character_set_server = 'utf8'");

$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$gestion_final_nombre = mysql_result(mysql_query("SELECT nombre_gestion from gestiones where codigo_gestion = $gestion_final"), 0, 0);

$productos_lista = $_GET['productos'];
$productos_lista_explode = explode(",", $productos_lista);

$nombres_productos = $_GET['codigos_productos'];
$nombres_productos = substr($nombres_productos, 0, -1);
$nombres_productos_explode = explode(",", $nombres_productos);

$productos_combinado = array_combine($productos_lista_explode, $nombres_productos_explode);
$count = 1;
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Cantidad de contactos x productos</title>
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Cantidad de contactos x productos</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Ciclo: <?php echo $ciclo_final; ?> | Gestión: <?php echo $gestion_final_nombre; ?></h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Productos: <?php echo $nombres_productos; ?></h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="eight columns centered">
                    <table border="1" class="aa">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Producto</th>
                            <th>Contactos</th>
                            <th>N&deg; MÃ©dicos</th>
                            <th>TOTAL</th>
                        </tr>
                        <?php  

                        foreach($productos_combinado as $codigo_prod => $nom_prod){

                            $cadena_especialidades = "";
                            $cadena_categoria_med  = "";
                            $cadena_linea          = "";
                            $sql = "SELECT DISTINCT p.cod_especialidad, p.categoria_med, p.codigo_linea from parrilla p, parrilla_detalle pd where p.codigo_parrilla = pd.codigo_parrilla and p.cod_ciclo = $ciclo_final and p.codigo_gestion = $gestion_final and pd.codigo_muestra = '$codigo_prod'";
                            $resp = mysql_query($sql);

                            while ($row_espe_cat_lin = mysql_fetch_array($resp)) {

                                $cadena_especialidades .= "'".$row_espe_cat_lin['0']."',";
                                $cadena_categoria_med  .= "'".$row_espe_cat_lin['1']."',";
                                $cadena_linea          .= "'".$row_espe_cat_lin['2']."',";

                            }

                            $cadena_especialidades = substr($cadena_especialidades, 0, -1);
                            $cadena_categoria_med  = substr($cadena_categoria_med, 0, -1);
                            $cadena_linea          = substr($cadena_linea, 0, -1);

                            $sql_cant_medicos = "SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and rmd.categoria_med in ($cadena_categoria_med) and rmd.cod_especialidad in ($cadena_especialidades)";
                            $resp_cant_contactos = mysql_query($sql_cant_medicos);
                            $num_medicos = mysql_result($resp_cant_contactos, 0, 0);

                            $sql_cant_contactos = "SELECT DISTINCT rmd.cod_med from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m where rmc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rmd.cod_contacto and m.cod_med = rmd.cod_med and rmc.cod_visitador = rm.cod_visitador and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and rmc.codigo_linea  in (1021) and rmd.cod_especialidad in ($cadena_especialidades)";
                            $resp_cant_contactos = mysql_query($sql_cant_contactos);
                            $num_contactos = mysql_num_rows($resp_cant_contactos);
                            ?>

                            <tr>
                                <td><?php echo $count; ?></td>
                                <td><?php echo $nom_prod; ?></td>
                                <td><?php echo $a = $num_contactos; ?></td>
                                <td><?php echo $num_medicos; ?></td>
                                <td><center><strong><?php echo ($a * $num_medicos); ?></strong></center></td>
                            </tr>
                            
                            <?php 
                            $count++;
                        } 
                        ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>