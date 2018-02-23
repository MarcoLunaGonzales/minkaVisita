<?php
error_reporting(0);
require("conexion.inc");
$cod_linea   = $_GET['linea'];
$territorios = $_GET['territorios'];

$codigo_ciclo = 6;
$codigo_gestion = 1010;

$territorios_explode = explode(",", $territorios);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Reporte frecuencia M&eacute;dicos</title>
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte frecuencia M&eacute;dicos</h3>
        </header>
        <?php  
        $cad_med = '';
        $sql_medicos_rutero = mysql_query("SELECT DISTINCT rmd.cod_med from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rma, rutero_maestro_detalle_aprobado rmd where rmc.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmd.cod_contacto and rmc.codigo_ciclo = $codigo_ciclo and rmc.codigo_gestion = $codigo_gestion and rmc.codigo_linea = 1021");
        while ($row_me_r = mysql_fetch_array($sql_medicos_rutero)) {
            $cad_med .= $row_me_r[0].",";
        }
        $cad_med = substr($cad_med, 0, -1);
        foreach ($territorios_explode as $territorio) {
            ?>
            <div class="row">
                <h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada; text-align: left">
                    <?php  
                    $sql_nom_ciudad = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $territorio");
                    echo mysql_result($sql_nom_ciudad, 0, 0);
                    $count = 1;
                    ?>
                </h3>
            </div>
            <div class="row">
                <?php
                $sql_medicos = mysql_query("SELECT m.cod_med, CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ', m.nom_med) as nombre, m.cod_catcloseup, m.cod_closeup, c.cod_especialidad, c.categoria_med, c.frecuencia_linea from medicos m, categorias_lineas c where m.cod_med = c.cod_med and m.cod_ciudad = $territorio and c.codigo_linea = $cod_linea and m.cod_med in ($cad_med) ORDER BY 2"); 
                // echo("SELECT m.cod_med, CONCAT(m.ap_pat_med,' ',m.ap_mat_med,' ', m.nom_med) as nombre, m.cod_closeup, c.cod_especialidad, c.categoria_med, c.frecuencia_linea from medicos m, categorias_lineas c where m.cod_med = c.cod_med and m.cod_ciudad = $territorio and c.codigo_linea = $cod_linea ORDER BY 2"); 
                ?>
                <table border="1">
                    <tr>
                        <th></th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Categoria Closeup</th>
                        <th>Closeup</th>
                        <th>Especialidad</th>
                        <th>Catgoria</th>
                        <th>Frecuencia</th>
                    </tr>
                    <?php 
                    while($row_medico = mysql_fetch_array($sql_medicos)){ 
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $row_medico[0]; ?></td>
                            <td><?php echo $row_medico[1]; ?></td>
                            <td><?php echo $row_medico[2]; ?></td>
                            <td><?php echo $row_medico[3]; ?></td>
                            <td><?php echo $row_medico[4]; ?></td>
                            <td><?php echo $row_medico[5]; ?></td>
                            <td><?php echo $row_medico[6]; ?></td>
                        </tr>
                        <?php 
                        $count++;
                    } 
                    ?>
                </table>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>