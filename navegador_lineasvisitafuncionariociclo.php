<?php
require("conexion.inc");
require("estilos_gerencia.inc");
$cod_linea_vis = $_REQUEST['cod_linea_vis'];
$sql_cab = "select nombre_l_visita from lineas_visita where codigo_l_visita='$cod_linea_vis'";
$resp_cab = mysql_query($sql_cab);
$dat_cab = mysql_fetch_array($resp_cab);
$nombre_linea_visita = $dat_cab[0];

$sql_gestion = mysql_query("Select codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
$codigo_gestion = mysql_result($sql_gestion, 0, 0);
$nombre_gestion = mysql_result($sql_gestion, 0, 1);
$sql_ciclos = mysql_query("Select cod_ciclo from ciclos where codigo_gestion = $codigo_gestion and codigo_linea = 1021 ");
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
            <h3>Seleccionar Ciclo para las lineas</h3>
            <h3>Línea de visita: <?php echo $nombre_linea_visita; ?> | Gestión: <?php echo $nombre_gestion ?> </h3>
        </header>
        <section id="body">
            <form action="navegador_lineasvisitafuncionario.php" method="get">
                <table width="20%" border="1">
                    <thead>
                        <tr>
                            <th>Ciclos:</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td align="center">
                                <select name="ciclo" id="ciclos">
                                    <?php while ($row_ciclo = mysql_fetch_array($sql_ciclos)) { ?>
                                        <option value="<?php echo $row_ciclo[0]; ?>"><?php echo $row_ciclo[0]; ?></option>
                                    <?php } ?>
                                </select>
                                <input type="hidden" value="<?php echo $cod_linea_vis; ?>" name="cod_linea_vis" />
                                <input type="hidden" value="<?php echo $codigo_gestion; ?>" name="codigo_gestion" />
                            </td>
                        </tr>
                    </tbody>
                </table>
                <input type="submit" class="boton" value="Proseguir" />
            </form>
            <table align='center'><tr><td><a href='navegador_lineas_visita.php'><img  border='0'src='imagenes/volver.gif' width='15' height='8'>Volver Atras</a></td></tr></table>
        </section>
    </center>

</body>
</html>