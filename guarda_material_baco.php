<?php
require_once ('baco/coneccion.php');
$codigo_baco = $_GET['codigo'];
$sql = mssql_query("select nombre_material, cod_hermes from materiales where cod_material = '$codigo_baco' ");
$nombre = mssql_result($sql, 0, 0);
$codigo_hermes = mssql_result($sql, 0, 1);
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
        <script type="text/javascript" language="javascript" src="ajax/baco_cod_hermes/send.data.js"></script>
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
                <table width="90%">
                    
                    <tr>
                        <th>Codigo Baco</th>
                        <th>Codigo Hermes</th>
                    </tr>
                    
                    <tr>
                        <td><?php echo $codigo_baco ; ?><input type="hidden" id="baco" value="<?php echo $codigo_baco  ?>" /></td>
                        <td><input type="text" id="codigo_hermes" value="<?php if($codigo_hermes == ''): echo '';  else: echo $codigo_hermes; endif; ?>" /></td>
                    </tr>
                    <tr>
                        <td><a href="#" id="guardar"> Guardar</a></td>
                    </tr>
                    
                </table>
            </section>
        </div>
    </body>
</html>