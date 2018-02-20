<?php
require("conexion.inc");
require("estilos_gerencia.inc");
$cod_linea_vis = $_REQUEST['cod_linea_vis'];
$sql_cab = "SELECT nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];

$sql_gestion = mysql_query("SELECT codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$nombre_gestion = mysql_result($sql_gestion, 0, 1);
$sql_ciclos = mysql_query("SELECT cod_ciclo from ciclos where codigo_gestion = $codigo_gestion and codigo_linea = 1032 ");
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css">
            table td, table th {
                padding: 5px 15px;
            }
            table {
                margin-bottom: 10px
            }
        </style>
    </head>
    <body>
    <center>
        <header>
            <h3>Seleccionar Ciclo para las lineas</h3>
            <!--<h3>Gesti&oacute;n: <?php echo $nombre_gestion ?> </h3>-->
        </header>
        <section id="body">
            <form action="navegador_lineas_visita.php" method="get">
                <table width="20%" border="1">
                    <thead>
                        <tr>
                            <th>Gestion:</th>
                            <td align="center">
                                <select name="gestion" id="gestion">
                                    <?php
                                    $sql_ges = mysql_query("select  DISTINCT nombre_gestion, codigo_gestion from gestiones ORDER BY 1 DESC");
                                    ?>
                                    <?php while ($row_gestion = mysql_fetch_array($sql_ges)) { ?>
                                        <option value="<?php echo $row_gestion[1]; ?>"><?php echo $row_gestion[0]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <th>Ciclos:</th>
                            <td align="center">
                                <select name="ciclo" id="ciclos">
                                    <?php while ($row_ciclo = mysql_fetch_array($sql_ciclos)) { ?>
                                        <option value="<?php echo $row_ciclo[0]; ?>"><?php echo $row_ciclo[0]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Linea:</th>
                            <td align="center">
                                <?php  
                                $sql_lineas = mysql_query("SELECT codigo_linea, nombre_linea  from lineas where estado = 1 and linea_promocion = 1 ORDER BY 2");
                                ?>
                                <select name="linea" id="linea">
                                    <?php while ($row_lineas = mysql_fetch_array($sql_lineas)) { ?>
                                        <option value="<?php echo $row_lineas[0]; ?>"><?php echo $row_lineas[1]; ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" class="boton" value="Proseguir" />
            </form>
        </section>
    </center>

</body>
</html>