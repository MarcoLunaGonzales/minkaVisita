<?php
error_reporting(0);
require("conexion.inc");
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" language="javascript" src="lib/funciones_banco_medicos_calculo.js"></script>
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
    </head>
    <body>
        <div id="container">
            <?php require("estilos2.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Realizar C&aacute;lculo Banco de Muestras</h3>
            </header>
            <article id="contenido"></article>
        </div>
    </body>
</html>