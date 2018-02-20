<?php
require("conexion.inc");
require("estilos_gerencia.inc");
$cod_linea_vis = $_REQUEST['cod_linea_vis'];
$sql_cab = "select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
$gestion = $_GET['gestion'];
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];

$sql_gestion = mysql_query("Select codigo_gestion, nombre_gestion from gestiones where codigo_gestion = $gestion ");
//echo("Select codigo_gestion, nombre_gestion from gestiones where codigo_gestion = $gestion ");
$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$nombre_gestion = mysql_result($sql_gestion, 0, 1);
$sql_ciclos = mysql_query("Select cod_ciclo from ciclos where codigo_gestion = $codigo_gestion and codigo_linea = 1021 ");
$sql_ciclos2 = mysql_query("Select cod_ciclo from ciclos where codigo_gestion = $codigo_gestion and codigo_linea = 1021 ");
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <center>
        <header>
            <h3>Seleccione los Ciclos Origen Destino para Replicar</h3>
            <h3>L&iacute;nea de visita: <?php echo $nombre_linea_visita; ?> | Gesti&oacute;n: <?php echo $nombre_gestion ?> </h3>
        </header>
        <section id="body">
            <form action="replicarLinea.php" method="get">
                <table width="40%" border="1">
                    <thead>
                        <tr>
                            <th>Ciclo Origen</th>
                            <th>Ciclo Destino</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center"><select name="ciclo_origen" id="ciclo_origen">
                                    <?php while ($row_ciclo = mysql_fetch_array($sql_ciclos)) { ?>
                                        <option value="<?php echo $row_ciclo[0]; ?>"><?php echo $row_ciclo[0]; ?></option>
                                    <?php } ?>
                                </select></td>
                            <td align="center"><select name="ciclo_destino" id="ciclo_destino">
                                    <?php while ($row_ciclo2 = mysql_fetch_array($sql_ciclos2)) { ?>
                                        <option value="<?php echo $row_ciclo2[0]; ?>"><?php echo $row_ciclo2[0]; ?></option>
                                    <?php } ?>
                                </select></td>
                        </tr>
                    </tbody>
                </table>
                <input type="hidden" value ="<?php echo $cod_linea_vis; ?>" name="cod_linea_vis" />
                <input type="hidden" value ="<?php echo $codigo_gestion; ?>" name="gestion" />
                <input type="submit" class="boton" value="Proseguir" />
            </form>
        </section>
    </center>
</body>
</html>