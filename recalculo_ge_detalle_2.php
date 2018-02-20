<?php
error_reporting(0);
require("conexion.inc");
$ciclo = $_GET['ciclo'];
$territorios = $_GET["territorios"];
$explode_ciclo = explode("|", $ciclo);
$codigo_ciclo = $explode_ciclo[0];
$codigo_gestion = $explode_ciclo[1];
?>
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
        #container {
            position: relative
        }
        table tbody tr:nth-child(2n) {
            background:  #e8e8e8;
        }
        .cajita {
            padding: 3px;
            background: white;
            border: 1px solid activeborder;
            cursor: pointer;
            margin: 4px 0
        }
        .cajita:hover {
            padding: 3px;
            background: #29677e;
            border: 1px solid activeborder;
            cursor: pointer;
            color: white;
        }
        table thead tr th, table tfoot tr td {
            font-size: 12px;
        }
        #boton {
            position: fixed;
            right: 10px;
            top: 50px;
            background: #fff;
            border: 1px solid activeborder;
            padding: 4px;
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
        #boton:hover span {
            color: white;
        }
        #enviar span {
            display: block;
        }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Grupos Especiales</h3>
            <h3 style="color: #5F7BA9; font-size: 1.2em; font-family: Vernada">Asignaci&oacute;n de visitadores a m&eacute;dicos</h3>
            <?php
            $sql_territorio = mysql_query("SELECT descripcion from ciudades where cod_ciudad in ($territorios)");
            while ($row_ciudad = mysql_fetch_array($sql_territorio)) {
                $territorios_finales .= $row_ciudad[0] . ",";
            }
            $territorios_finales_sub = substr($territorios_finales, 0, -1);
            $territorios_finales_explode = explode(",", $territorios_finales_sub);
            ?>
            <p style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; text-transform: capitalize">Para el ciclo: <?php echo $codigo_ciclo; ?> | Territorio(s): <?php echo $territorios_finales_sub; ?></p>
        </header>
        <section role="main">
            <div class="row">
                <?php foreach ($territorios_finales_explode as $terri) { ?>
                <div class="twelve columns">
                    <h3 style="color: #5F7BA9; font-size: 1.3em; font-family: Vernada; text-align: left"><?php echo $terri; ?></h3>
                    <?php
                    $contador_row = 1;
                    $sql_cod_ci = mysql_query("SELECT cod_ciudad from ciudades where descripcion = '$terri'");
                    $cod_ciudad_final = mysql_result($sql_cod_ci, 0, 0);
                    $sql_ge = mysql_query("SELECT g.codigo_grupo_especial, g.nombre_grupo_especial from grupo_especial g where g.codigo_linea='1021' and g.agencia='$cod_ciudad_final'order by g.nombre_grupo_especial ");
                    if (mysql_num_rows($sql_ge) == 0) {
                        echo "<p>No hay grupos especiales en esta regional</p>";
                    }
                    while ($row_ge = mysql_fetch_array($sql_ge)) {
                        $sql_medico = mysql_query("SELECT DISTINCT gd.cod_med, concat(m.ap_pat_med, ' ',m.ap_mat_med,' ',m.nom_med) as nom, gd.cod_visitador, concat(f.paterno, ' ',f.materno,' ',f.nombres) as nom_vis from grupos_especiales g, grupos_especiales_detalle gd, medicos m, funcionarios f where g.id = gd.id and gd.cod_med = m.cod_med and f.codigo_funcionario = gd.cod_visitador and g.territorio = '$cod_ciudad_final' AND g.codigo_grupo_especial = $row_ge[0] and g.ciclo = $codigo_ciclo and g.gestion = $codigo_gestion ORDER BY nom");
                        ?>
                        <div class="six columns end">
                            <h3 style="color: #5F7BA9; font-size: 1.0em; font-family: Vernada; text-align: left"><?php echo $row_ge[1]; ?></h3>
                            <table border="1" id="<?php echo $row_ge[0] . "-" . $cod_ciudad_final ?>">
                                <thead>
                                    <tr>
                                        <th>Ruc</th>
                                        <th>M&eacute;dico</th>
                                        <!-- <th>Codigo Visitador</th> -->
                                        <th>Visitador Oficial</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    while ($row_medicos = mysql_fetch_array($sql_medico)) {
                                        ?>
                                        <tr>
                                            <td><?php echo $row_medicos[0]; ?></td>
                                            <td><?php echo $row_medicos[1]; ?></td>
                                            <!-- <td><?php echo $row_medicos[2]; ?></td> -->
                                            <td><?php echo $row_medicos[3]; ?></td>
                                        </tr>
                                        <?php } ?>
                                    </tbody>
                                </table>
                            </div>
                            <?php
                            $contador_row++;
                        }
                        ?>
                    </div>
                    <?php
                } 
                ?>
            </div>
        </section>
    </div>
    <div class="modal"></div>
</body>
</html>