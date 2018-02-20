<?php
require("conexion.inc");
require("estilos_gerencia.inc");


$sql_gestion = mysql_query("Select codigo_gestion, nombre_gestion from gestiones where estado = 'Activo' ");
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
            <h3>Seleccione los Ciclos Origen Destino para Replicar (Todas las lineas)</h3>
            <h3>Línea de visita: <strong style="color:red">Todas</strong> | Gestión: <?php echo $nombre_gestion ?> </h3>
        </header>
        <section id="body">
            <form action="replicarLineaTodo.php" method="get">
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
                <input type="hidden" value ="<?php echo $codigo_gestion; ?>" name="gestion" />
                <input type="submit" class="boton" value="Proseguir" />
            </form>
        </section>
    </center>
</body>
</html>