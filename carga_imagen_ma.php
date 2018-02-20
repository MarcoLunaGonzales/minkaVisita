<?php
require_once ('conexion.inc');
$codigo = $_GET['codigo'];
$sql = mysql_query("select descripcion_material, codigo_material from material_apoyo where codigo_material = '$codigo' ");
$nombre = mysql_result($sql, 0, 0);
$codigo_material = mysql_result($sql, 0, 1);
?>
<!DOCTYPE HTML>
<html lang="es-US">
    <head>
        <meta charset="iso-8859-1">
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <style type="text/css">
            tr th { background: #E6E6E6}
            tr td { background: #EAEBFF}
            input {
                width: 300px
            }
        </style>
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" language="javascript" src="ajax/imagenes_subir/send.data.js"></script>
        <link rel="stylesheet" href="lib/assets/css/styles.css" />
        <title>Guardar Materiales Baco</title>
        <script type="text/javascript">
            $(document).ready(function(){
                $("#guardar").click(function(){
                    sendData();
                })
            })
        </script>
    </head>
    <body>
        <div id="container">
            <header id="titulo">
                <h3><?php echo $nombre; ?></h3>
            </header>
            <section role="main">
                <div id="dropbox">
                    <span class="message">Dejar las Imagenes Aqu&iacute;. <br /><i>(apareceran al momento de dejarlas)</i></span>
                </div>
                <input type="hidden" id="nombre_pic" />
                <input type="hidden" id="codigo_material" value="<?php echo $codigo_material; ?>" />
                <center>
                    <input type="button" id="guardar" value="Guardar" />
                </center>
            </section>
        </div>
        <script src="lib/assets/js/jquery.filedrop.js"></script>

        <!-- The main script file -->
        <script src="lib/assets/js/script.js"></script>
    </body>
</html>