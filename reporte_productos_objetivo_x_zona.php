<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Productos Objetivo X Zona</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    $(document).ready(function() {
        $("#enviar").click(function(){
            var territorio, ciclo_gestion;
            territorio    = $("#territorio").val();
            ciclo_gestion = $("#ciclo").val();
            window.location.href = "reporte_productos_objetivo_x_zonas_detalle.php?&territorio="+territorio+"&ciclo_gestion="+ciclo_gestion;
        })                
    });
    </script>
    <style type="text/css">
    #contenido tr th {
        padding: 5px
    }
    .controls input, .controls select {
        padding: 0
    }
    input[type="button"] {
        margin: 10px 0;
        cursor: pointer;
        background: #fff;
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Productos Objetivo X Zona</h3>
        </header>
        <div id="contenido">
            <center>
                <table border="1">
                    <tr>
                        <th>Territorio</th>
                        <td>
                            <select name="territorio" id="territorio" size="14">
                                <?php
                                $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad <> 115 order by c.descripcion");
                                while ($dat_t = mysql_fetch_array($sql_territorio)) {
                                    $codigo_ciudad = $dat_t[0];
                                    $nombre_ciudad = $dat_t[1];
                                    ?>
                                    <option value="<?php echo $codigo_ciudad ?>"> <?php echo $nombre_ciudad ?></option>
                                    <?php
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Ciclo Gestion</th>
                        <td>
                            <select name="ciclo" id="ciclo" size="10">
                                <?php
                                $sql_ciclo = mysql_query("SELECT p.ciclo, p.gestion, g.nombre_gestion from productos_objetivo_cabecera p, gestiones g where p.gestion = g.codigo_gestion");
                                while ($dat_c = mysql_fetch_array($sql_ciclo)) {
                                    $ciclo          = $dat_c[0];
                                    $gestion        = $dat_c[1];
                                    $nombre_gestion = $dat_c[2];
                                    ?>
                                    <option value="<?php echo $ciclo.",".$gestion ?>"> <?php echo $ciclo." ".$nombre_gestion ?></option>
                                    <?php
                                } 
                                ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <input type="button" id="enviar" value="Ver" />
            </center>
        </div>
    </div>
</body>
</html>