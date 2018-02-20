<?php
error_reporting(0);
require("conexion.inc");
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
            var territorio;
            territorio    = $("#territorio").val();
            window.location.href = "visitadores_x_zonas_detalle.php?&territorio="+territorio;
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Visitadores x Zonas</h3>
        </header>
        <div id="contenido">
            <center>
                <table border="1">
                    <tr>
                        <th>Territorio</th>
                        <td>
                            <select name="territorio" id="territorio" size="14" multiple>
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
                </table>
                <input type="button" id="enviar" value="Ver" />
            </center>
        </div>
    </div>
</body>
</html>