<?php
error_reporting(0);
require("conexion.inc");
$id = $_GET['id'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <style type="text/css">
            table th {
                padding: 10px 5px
            }
            a:hover {
                text-decoration: underline
            }
            table tbody tr:nth-child(2n) {
                background: #d0d0d0;
            }
            span {
                font-weight: bold
            }
        </style>
    </head>
    <body>
        <div id="container">
            <?php require("estilos2.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Plan de L&iacute;neas Vista</h3>
            </header>
            <!-- <div id="contenido"> -->
                <div class="row" style="width:100%" id="tab1">
                    <div class="twelve columns">
                        <table border="1">
                            <tr>
                                <th>&nbsp;</th>
                                <?php
                                $sql_ciudades = mysql_query("SELECT DISTINCT ciudad from plan_lineas where id_cab = $id ");
                                while ($row_ciudad = mysql_fetch_array($sql_ciudades)) {
                                    ?>
                                    <th>
                                        <?php
                                        $sql_nom_ciudad = mysql_query("SELECT descripcion from ciudades where cod_ciudad = $row_ciudad[0] ");
                                        echo mysql_result($sql_nom_ciudad, 0, 0);
                                        $ciudades_finales .= $row_ciudad[0] . ",";
                                        ?>
                                    </th>
                                <?php } ?>
                            </tr>
                            <?php
                            $ciudades_finales_sub = substr($ciudades_finales, 0, -1);
                            $ciudades_finales_explode = explode(",", $ciudades_finales_sub);
                            $sql_especialidades = mysql_query("SELECT DISTINCT especialidad from plan_lineas_detalle where id_cab = $id");
                            while ($row_espe = mysql_fetch_array($sql_especialidades)) {
                                ?>
                                <tr>
                                    <th><?php echo $row_espe[0]; ?></th>
                                    <?php
                                    foreach ($ciudades_finales_explode as $ciudades) {
                                        $sql_lineas = mysql_query("SELECT pd.linea, pd.especialidad,lv.nom_orden from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv where pc.id = p.id_cab and p.id = pd.id and pd.linea = lv.codigo_l_visita and p.ciudad = $ciudades and pc.id = $id and pd.especialidad = '$row_espe[0]'");
                                        $sql_plan = mysql_query("SELECT count(pd.especialidad) from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd WHERE pc.id =p.id_cab and p.id = pd.id and pc.estado = 1 and p.ciudad = $ciudades and pd.especialidad = '$row_espe[0]' ");
                                        $cuantos = mysql_result($sql_plan, 0, 0);
                                        echo "<td>";
                                            while ($row_nom_linea = mysql_fetch_array($sql_lineas)) {
                                                $nombreLineaVisita1 = $row_nom_linea[1];
                                                $nombreLineaVisita = $row_nom_linea[2];
                                                $ultimo = $nombreLineaVisita[strlen($nombreLineaVisita)-1];
                                                if($ultimo == 'U'){$ultimo = 1;}
                                                echo "<span>".$nombreLineaVisita1." ".$ultimo."-".$cuantos."</span> <br />"  ;
                                            }
                                        echo "</td>";
                                    }
                                    ?>
                                </tr>
                                <?php
                            }
                            ?>
                        </table>
                    </div>
                </div>
            <!-- </div> -->
            <div class="row">
                <dif class="three columns small-centered">
                    <a href="plan_de_lineas_detalle.php" class="button">Volver atr&aacute;s</a>
                </dif>
            </div>
        </div>
    </body>
</html>