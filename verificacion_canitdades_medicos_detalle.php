<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");
$ciclo_gestion = $_GET['ciclo_gestion'];
$lineaa        = $_GET['linea'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo = $ciclo_gestion_explode[0];
$gestionn = $ciclo_gestion_explode[1];
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Verificacion medicos y lineas</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/jquery.fixedtable.js"></script>
    <style type="text/css">
    h4 {
        text-align: left
    }
    table thead tr th {
        padding: 5px 10px;
        /*color: #084B8A */
    }
    table tbody tr td {
        padding: 5px 10px
    }
    table tbody tr th {
        color: #000
    }
    table .intermedio th {
        background: #53c4ee;
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
    <script>

    </script>
</head>
<body>
    <div id="container">
        <?php 
        require("estilos3.inc"); 
        ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Verificacion M&eacute;dicos y l&iacute;neas</h3>
        </header>
        <?php  
        $sql_ciudad = mysql_query("SELECT cod_ciudad, descripcion from ciudades where cod_ciudad <> 115 ORDER BY 2");
        while ( $row_ciudad = mysql_fetch_array($sql_ciudad) ) {
            ?>
            <div class="row">
                <div class="twelve columns">
                    <h2 style="font-size: 1.1em; text-align: left"><?php echo $row_ciudad[1]; ?></h2>
                    <table>
                        <tr>
                            <th></th>
                            <th>M&eacute;dico</th>
                            <th>Especialidad</th>
                            <th>Funcionario</th>
                            <th>L&iacute;nea</th>
                        </tr>
                        <?php  
                        $count = 1;
                        $sql_datos = mysql_query("SELECT DISTINCT rmd.cod_med, CONCAT(m.ap_pat_med, ' ' ,m.ap_mat_med, ' ', m.nom_med) as nombre_medico, rmd.cod_especialidad, rmc.cod_visitador, CONCAT(f.paterno, ' ',f.materno, ' ' ,f.nombres) as nombre_funcionario from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rma, rutero_maestro_detalle_aprobado rmd, funcionarios f, medicos m WHERE rmc.cod_rutero = rma.cod_rutero and rma.cod_contacto = rmd.cod_contacto and f.codigo_funcionario = rmc.cod_visitador and rmd.cod_med = m.cod_med and rmc.codigo_ciclo = $ciclo and rmc.codigo_gestion = $gestionn and f.cod_ciudad = $row_ciudad[0] and rmc.codigo_linea = $lineaa and rmc.estado_aprobado = 1 ORDER BY 2");
                        while ($row_data = mysql_fetch_array($sql_datos)) {
                            $sql_linea = mysql_query("SELECT lvv.codigo_l_visita, lv.nom_orden from lineas_visita_visitadores lvv, lineas_visita lv, lineas_visita_especialidad le where lv.codigo_l_visita = lvv.codigo_l_visita and le.codigo_l_visita = lvv.codigo_l_visita and lvv.codigo_funcionario = $row_data[3] and lvv.codigo_ciclo = $ciclo and lvv.codigo_gestion = $gestionn and  le.cod_especialidad = '$row_data[2]'");
                            $nom_lineas_fun = '';
                            $cod_lineas_fun = '';
                            while ($row_lineas = mysql_fetch_array($sql_linea)) {
                                $cod_lineas_fun .= $row_lineas[0] .",";
                                $nom_lineas_fun .= $row_lineas[1] .",";
                            }
                            $nom_lineas_fun = substr($nom_lineas_fun, 0, -1);
                            $cod_lineas_fun = substr($cod_lineas_fun, 0, -1);
                            ?>
                            <tr>
                                <td><?php echo $count ?></td>
                                <td><?php echo $row_data[1] ?></td>
                                <td><?php echo $row_data[2] ?></td>
                                <td><?php echo $row_data[4] ?></td>
                                <td><?php echo $nom_lineas_fun; ?></td>
                            </tr>
                            <?php  
                            $count++;
                        }
                        ?>
                    </table>
                </div>
            </div>
            <?php  
        }
        ?>
    </div>
    <div class="modal"></div>
</body>
</html>