<?php
/**
 * Desarrollado por Datanet-Bolivia.
 * @autor: Marco Antonio Luna Gonzales
 * Sistema de Visita M&eacute;dica
 * * @copyright 2005 
 */
require("conexion.inc");

////	$sql="select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g 	
////			  where c.`codigo_gestion`=g.`codigo_gestion` and g.`estado`='Activo' order by 1 desc";
//$sql = "s   c.`codigo_gestion`=g.`codigo_gestion` order by g.codigo_gestion desc, c.cod_ciclo  desc limit 12";
///* $sql="select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g 	
//  where c.`codigo_gestion`=g.`codigo_gestion` and g.codigo_gestion=1007 order by 1 desc"; */

?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <title></title>
        <style type="text/css" href="lib/noty/buttons.css"></style>

    </head>
    <body background="imagenes/fondo_pagina.jpg">
        <?php require("header.php"); ?>
        <?php
        //$sql = "select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g where 
         //   c.`codigo_gestion`=g.`codigo_gestion` and g.estado = 'Activo' order by g.codigo_gestion desc, c.cod_ciclo  desc limit 12";
        $sql = "select distinct(c.`cod_ciclo`), g.`codigo_gestion`, g.`nombre_gestion` from `ciclos` c, gestiones g where 
            c.`codigo_gestion`=g.`codigo_gestion` and g.codigo_gestion=1013 order by g.codigo_gestion desc, c.cod_ciclo  desc limit 12";
		$resp = mysql_query($sql);
        ?>
    <center>
        <h2>Ciclos</h2>
    </center>
    <center>
        <form action="" method="post">
            <table border="1" class="texto" cellspacing="0" width="60%">
                <tr>
                    <th>Ciclo</th>
                    <th>Registrar Ingreso</th>
                    <th>Ver Detalle</th>
                    <th>Enviar Muestras</th>
                    <th>Enviar Material</th>
                </tr>
                <?php
                while ($row = mysql_fetch_array($resp)) {
                    $codigo = $row[0];
                    $codGestion = $row[1];
                    $nombreGestion = $row[2];
                    ?>
                    <tr>
                        <td align="center"><?php echo $codigo; ?> | <?php echo $nombreGestion; ?></td>
                        <td align="center"><a href="navegador_devolucionVisitador.php?cod_ciclo=<?php echo $codigo; ?>&cod_gestion=<?php echo $codGestion; ?>">Registrar</a></td>
                        <td align="center"><a href="navegador_devolucionVisitador2.php?cod_ciclo=<?php echo $codigo; ?>&cod_gestion=<?php echo $codGestion; ?>">Ver</a></td>
                        <td align="center"><a href="javascript:void(0)" class="enviar" id="<?php echo $codigo."|".$codGestion ?>">Enviar MM por ciclo</a></td>
                        <td align="center"><a href="javascript:void(0)" class="enviar_ma" id="<?php echo $codigo."|".$codGestion ?>">Enviar MA por ciclo</a></td>
                    </tr>
                <?php } ?>
            </table>
        </form>
    </center>
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript" src="lib/noty/jquery.noty.js"></script>
    <script type="text/javascript" src="lib/noty/center.js"></script>
    <script type="text/javascript" src="lib/noty/default.js"></script>
    <script type="text/javascript" src="ajax/veriDevolucion/send.data.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            $(".enviar").click(function(){
                sendData($(this).attr('id'));
            })
            $(".enviar_ma").click(function(){
                sendData2($(this).attr('id'));
            })
        })
    </script>
</body>
</html>