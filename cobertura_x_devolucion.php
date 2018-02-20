<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Cobertura Vs Devoluciones</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <style>
    tr th {
        padding: 5px 10px;
    }
    </style>
    <script>
    var windowSizeArray = [ "width=1000,height=700,scrollbars=yes" ];
    jQuery(document).ready(function($) {
        $("#continuar").click(function(){
            var ciclo,territorios,linea;
            ciclo = $("#ciclo").val();
            territorios = $("#territorio").val();
            linea = $("#linea").val();
            // alert(ciclo+" "+territorios)
            var url = $(this).attr("rel")+"?ciclo="+ciclo+"&territorios="+territorios+"&linea="+linea;
            var windowName = "popUp";//$(this).attr("name");
            var windowSize = windowSizeArray[0];
            window.open(url, windowName, windowSize);

            // event.preventDefault();
        })
    });
    </script>
</head>
<body>
    <div id="container">
        <?php require("estilos2.inc"); ?>
        <header id="titulo" style="min-height: 50px; margin-top:15px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Cobertura Vs Devoluci&oacute;n por Ciclo</h3>
        </header>
        <div id="contenido">
            <center>
                <table border="1">
                    <tr>
                        <th>Ciclo</th>
                        <td>
                            <?php $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15"); ?>
                            <select name="ciclo" id="ciclo">
                                <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>Territorio</th>
                        <td>
                            <?php $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c, `funcionarios_agencias` f where f.`cod_ciudad`=c.`cod_ciudad` and f.`codigo_funcionario`=$global_usuario order by c.descripcion"); ?>
                            <select name="territorio" id="territorio" multiple size="12">
                                <?php while ($row_territorio = mysql_fetch_array($sql_territorio)) { ?>
                                <option value="<?php echo $row_territorio[0]; ?>"><?php echo $row_territorio[1]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <th>L&iacute;nea</th>
                        <td>
                            <?php $sql_linea = mysql_query("SELECT codigo_linea, nombre_linea from lineas where linea_promocion = 1 and estado = 1 order by 2 "); ?>
                            <select name="linea" id="linea">
                                <?php while ($row_linea = mysql_fetch_array($sql_linea)) { ?>
                                <option value="<?php echo $row_linea[0]; ?>"><?php echo $row_linea[1]; ?></option>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>
                </table>
                <center><a href="javascript:void(0)" rel="cobertura_x_devolucion_detalle.php" class="button" id="continuar">Continuar</a></center>
            </center>
        </div>
    </div>
</body>
</html>