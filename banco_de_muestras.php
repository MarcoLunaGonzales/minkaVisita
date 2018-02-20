<?php
require("estilos.inc");
error_reporting(0);
set_time_limit(0);
?>
<!DOCTYPE HTML>
<html lang="es-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Cargado Materiales Baco</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" language="javascript" src="lib/funciones_banco_medicos.js"></script>
        <link type="text/css" href="css/tables.css" rel="stylesheet" />
        <script type="text/javascript" language="javascript" src="lib/jquery.dataTables.js"></script>
    </head>
    <body>
        <div id="container">
            <header id="titulo">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Banco de Muestras</h3>
            </header>
            <article id="contenido"></article>
        </div>
    </body>
</html>