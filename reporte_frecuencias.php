<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Reporte Frecuencia</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#continuar").click(function(){
            var url,territorios,linea;
            url                  = $("#continuar").attr('rel');
            linea                = $("#linea").val();
            territorios          = $("#territorio").val();
            window.location.href = url+"?&territorios="+territorios+"&linea=1021";
        })  
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Frecuencia x M&eacute;dico</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <table border="1">
                            <!-- <tr>
                                <th>L&iacute;nea</th>
                                <td>
                                    <?php $sql_linea = mysql_query("SELECT codigo_linea, nombre_linea from lineas where linea_promocion = 1 and estado = 1 ORDER BY 2") ?>
                                    <select name="linea" id="linea">
                                        <?php while($row_linea = mysql_fetch_array($sql_linea)){ ?>
                                        <option value="<?php echo $row_linea[0] ?>"><?php echo $row_linea[1] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr> -->
                            <tr>
                                <th>Territorio</th>
                                <td>
                                    <?php $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad <> 115 order by c.descripcion") ?>
                                    <select name="territorio" id="territorio" multiple size="14">
                                        <?php while ($row_territorio = mysql_fetch_array($sql_territorio)) { ?>
                                        <option value="<?php echo $row_territorio[0]; ?>"><?php echo $row_territorio[1]; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:void(0)" rel="reporte_frecuencias_detallado.php" class="button" id="continuar">Continuar</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</body>
</html>