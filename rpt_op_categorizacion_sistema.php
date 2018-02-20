<?php require("conexion.inc"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="stilos.css" type="text/css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="ajax/reportecategorizacion/send.data.js"></script>
        <script type="text/javascript">
            var windowSizeArray = [ "width=1000,height=800,scrollbars=yes" ];
            $(function() {
                $("#enviar").click(function(event){
                    var territorio = $("#territorio").val();
                    var gestion = $("#gestion").val();
                    if(territorio == '' || territorio == null){
                        alert("Debe seleccionar al menos un territorio")
                    }else{
                        var url = "rpt_categorizacionmedicosistema.php?territorio="+territorio+"&gestion="+gestion;
                        var windowName = "Reporte Categorizacion Medicos por el sistema";//$(this).attr("name");
                        var windowSize = windowSizeArray[$(this).attr("rel")];
                    
                        window.open(url, windowName, windowSize);
 
                        event.preventDefault();
                    }
                })
            })
        </script>
        <style type="text/css">
            #header h2 { color: #5F7BA9; font-size: 11pt; font-family: Verdana}
            body { background: #fff url('imagenes/fondo_pagina.jpg') no-repeat right; }
            #filtro th { text-align: left;; width: 30%; font-size: 12px }
        </style>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <center>
                    <h2>Reporte Categorizaci&oacute;n de M&eacute;dicos</h2>
                </center>
            </div>
            <div id="content">
                <center>
                    <table border="1" width="30%" cellpadding="5" id="filtro">
                        <tr>
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio" size="10" multiple>
                                    <?php
                                    $sql_territorio = mysql_query("select cod_ciudad, descripcion from ciudades where cod_ciudad !=115  order by descripcion  ASC");
                                    while ($row_territorio = mysql_fetch_array($sql_territorio)) {
                                        $codigo_ciudad = $row_territorio[0];
                                        $nombre_ciudad = $row_territorio[1];
                                        ?>
                                        <option value="<?php echo $codigo_ciudad; ?>"><?php echo $nombre_ciudad; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Gesti&oacute;n - Ciclo</th>
                            <td>
                                <select name="gestion" id="gestion">
                                    <option value="vacio">Seleccione una opci&oacute;n</option>
                                    <?php
                                    $sql_gestion = mysql_query(" select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15 ");
                                    while ($row_gestion = mysql_fetch_assoc($sql_gestion)) {
                                        $codigo_ciclo = $row_gestion['cod_ciclo'];
                                        $codigo_gestion = $row_gestion['codigo_gestion'];
                                        $nombre_gestion = $row_gestion['nombre_gestion'];
                                        ?>
                                        <option value="<?php echo $codigo_ciclo . "|" . $codigo_gestion . "|" . $nombre_gestion; ?>"><?php echo $codigo_ciclo . " " . $nombre_gestion; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                </center>
                <center style="padding:20px 0">
                    <input type='button' name='reporte' rel="0" value='Ver Reporte' class='boton' id="enviar" />
                </center>
            </div>
        </div><!-- container -->
    </body>
</html>