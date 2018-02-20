<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Cambio Datos Funcionario</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
            });
        </script>
        <style type="text/css">
        </style>
    </head>
    <body>
        <div id="container">
            <h2>Registro de cambios en funcionarios</h2>
            <div class="row">
                <?php 
                $sql_cambios = mysql_query("select * from cambio_nombre_funcionario");
                ?>
                <table border="1">
                    <tr>
                        <th></th>
                        <th>Codigo</th>
                        <th>Nombre</th>
                        <th>Fecha</th>
                    </tr>
                    <?php 
                    while($row = mysql_fetch_array($sql_cambios)){
                       ?>
                    <tr>
                        <td></td>
                        <td><?php echo $row[0] ?></td>
                        <td><?php echo $row[1]." ".$row[3]." ". $row[4] ?></td>
                        <td><?php echo $row[5] ?></td>
                    </tr>
                    <?php 
                    }
                    ?>
                </table>
            </div>
        </div>
    </body>
</html>