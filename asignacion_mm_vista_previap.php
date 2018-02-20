<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Cantidades de MM</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#vista_previa").click(function(){
            var windowSizeArray = [ "scrollbars=yes" ];
            var ciclo_gestion, linea;
            ciclo_gestion = $("#ciclo").val();
			linea = $("#linea").val();
            var url = $(this).attr("rel")+"?ciclo_gestion="+ciclo_gestion+"&linea="+linea;
            var windowName = "popUp";//$(this).attr("name");
            var windowSize = windowSizeArray[0];
            window.open(url, windowName, windowSize);
        })
    });
    </script>
    <style type="text/css">
    h4 {
        text-align: left
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Asignacion De Cantidades de MM</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <table border="1">
                            <tr>
                                <th>Ciclo - Gestion Destino</th>
                                <td>
                                    <?php $sql_ciclo = mysql_query("SELECT DISTINCT (c.ciclo), c.gestion, g.nombre_gestion FROM asignacion_mm_excel c, gestiones g 
                                    WHERE c.gestion = g.codigo_gestion ORDER BY g.codigo_gestion DESC, c.ciclo DESC LIMIT 0, 15") ?>
                                    <select name="ciclo" id="ciclo">
                                        <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                        <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
							<tr>
                                <th>L&iacute;nea</th>
                                <td>
                                    <?php 
                                    $sql_linea = mysql_query("SELECT codigo_linea, nombre_linea from lineas where estado = 1 and linea_promocion = 1 ORDER BY 2"); 
                                    ?>
                                    <select name="linea" id="linea">
                                        <?php 
                                        while($row_linea = mysql_fetch_array($sql_linea)){ 
                                            ?>
                                            <option value="<?php echo $row_linea[0]; ?>"><?php echo $row_linea[1]; ?></option>
                                            <?php 
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
							
                        </table> 
                        <a href="javascript:void(0)" rel="llenar_parrilla_asignacion_mm_p1.php" class="button" id="vista_previa">Vista Previa</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="modal"></div>

</body>
</html>