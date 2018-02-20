<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion De Productos</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {
        $("#continuar").click(function(){
            var url,territorios,estado,ciclo_inicio,ciclo_destino;
            estado = $("#estado").val();
			ciclo_inicio = $("#ciclo_inicio").val();
            ciclo_destino = $("#ciclo_destino").val();
            url = $("#continuar").attr('rel');
            territorios = $("#territorio").val();
            window.location.href = url+"?&territorios="+territorios+"&estado="+estado+"&ciclo_inicio="+ciclo_inicio+"&ciclo_destino="+ciclo_destino;
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
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Reporte Estado M&eacute;dicos</h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="twelve columns">
                    <center>
                        <table border="1">
                            <tr>
                                <th>Territorio</th>
                                <td>
                                    <?php $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad <> 115 order by c.descripcion") ?>
                                    <select name="territorio" id="territorio" multiple size="12">
                                        <?php while ($row_territorio = mysql_fetch_array($sql_territorio)) { ?>
                                        <option value="<?php echo $row_territorio[0]; ?>"><?php echo $row_territorio[1]; ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Estado Medicos</th>
                                <td>
                                    <select name="estado" id="estado">
                                        <option value="0">Todos</option>
                                        <option value="1">Pre-Aprobados</option>
                                        <option value="2">Aprobados</option>
                                        <option value="3">Rechazados</option>
                                    </select>
                                </td>
                            </tr>
							<tr>
                                <th>Ciclo - Gestion Inicio</th>
                                <td>
                                    <?php $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g  where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11") ?>
                                    <select name="ciclo_inicio" id="ciclo_inicio">
                                        <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                        <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <th>Ciclo - Gestion Destino</th>
                                <td>
                                    <?php $sql_ciclo = mysql_query("SELECT distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g  where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11") ?>
                                    <select name="ciclo_destino" id="ciclo_destino">
                                        <?php while($row_ciclo = mysql_fetch_array($sql_ciclo)){ ?>
                                        <option value="<?php echo $row_ciclo[0]."-".$row_ciclo[1] ?>"><?php echo $row_ciclo[0]." ".$row_ciclo[2] ?></option>
                                        <?php } ?>
                                    </select>
                                </td>
                            </tr>
                        </table>
                        <a href="javascript:void(0)" rel="estado_medicos_bm.php" class="button" id="continuar">Continuar</a>
                    </center>
                </div>
            </div>
        </div>
    </div>
</body>
</html>