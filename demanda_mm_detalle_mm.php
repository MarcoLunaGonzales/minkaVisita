<?php
require_once ('conexion.inc');
$codigo = $_GET['codigo'];
$nombre = $_GET['nombre'];
$fecha = $_GET["fecha"];
$sql = mysql_query("SELECT CONCAT(a.descripcion,' ',a.presentacion) , b.existencias,b.stock_minimo,b.stock_reposicion,b.stock_maximo,b.demanda, b.codigo_muestra from muestras_medicas a, demanda_mm_detalle b where a.codigo = b.codigo_muestra and b.id = '$codigo' ");
$count = 1;
?>
<!DOCTYPE HTML>
<html lang="es-US">
    <head>
        <meta charset="iso-8859-1">
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <link rel="stylesheet" href="lib/assets/css/styles.css" />
        <title>Demanda MM Detalle</title>
    </head>
    <body>
        <div id="container">
            <header id="titulo">
                <h3><?php echo $nombre; ?></h3>
                <h3>A la Fecha: <?php echo $fecha; ?></h3>
            </header>
            <section role="main">
                <center>
                    <table border="1">
                        <thead>
                            <tr>
                                <th></th>
                                <th>C&oacute;digo Muestra</th>
                                <th>Nombre Muestra</th>
                                <th>Existencias</th>
                                <th>Stock Minimo</th>
                                <th>Stock Reposicion</th>
                                <th>Stock Maximo</th>
                                <th>Demanda</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysql_fetch_array($sql)) { ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row[6]; ?></td>
                                    <td><?php echo $row[0]; ?></td>
                                    <td><?php echo $row[1]; ?></td>
                                    <td><?php echo $row[2]; ?></td>
                                    <td><?php echo $row[3]; ?></td>
                                    <td><?php echo $row[4]; ?></td>
                                    <td><?php echo $row[5]; ?></td>
                                </tr>
                                <?php $count++; ?>
                            <?php } ?>
                        </tbody>
                    </table>
                </center>
            </section>
        </div>
    </body>
</html>