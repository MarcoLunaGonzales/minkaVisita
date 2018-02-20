<?php require("conexion.inc"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="stilos.css" type="text/css" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="ajax/reportecategorizacion/send.data.js"></script>
        <script type="text/javascript" src="lib/cal.js"></script>
        <script type="text/javascript">
            var windowSizeArray = [ "width=1000,height=700,scrollbars=yes" ];
            $(document).ready(function() {
                $('input.one').simpleDatepicker({ startdate: 2012, enddate: 2014 });
                $("#verReporte").click(function(){
                    var territorio = $("#territorio").val();
                    var estado  = $("#estado").val();
                    var fecha1  = $("#fecha1").val();
                    var fecha2  = $("#fecha2").val();
                    
                    var url = "rpt_altamedicofecha.php?territorio="+territorio+"&estado="+estado+"&fecha1="+fecha1+"&fecha2="+fecha2;
                    var windowName = "Reporte Cobertura por medico";
                    var windowSize = windowSizeArray[0];
                    
                    window.open(url, windowName, windowSize);
                })  
            });
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
                    <h2>Alta de M&eacute;dicso por Fecha</h2>
                </center>
            </div>
            <div id="content">
                <center>
                    <table border="1" width="60%" cellpadding="5" id="filtro">
                        <tr>
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio">
                                    <option value="vacio">Seleccione una opci&oacute;n</option>
                                    <?php
                                    $sql_territorio = mysql_query("select cod_ciudad, descripcion from ciudades where cod_ciudad <> 115 order by descripcion  ASC");
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
                            <th>Estado de registro</th>
                            <td>
                                <select name="estado" id="estado">
                                    <option value="1">Fecha registro</option>
                                    <option value="2">Fecha Pre Aprobado</option>
                                    <option value="3">Fecha Aprobado</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Rango de fechas</th>
                            <td>
                                <input class="one" type="text" name="date" value="" id="fecha1" />
                                <input class="one" type="text" name="date2" value="" id="fecha2" />
                            </td>
                        </tr>
                    </table>
                </center>
                <center style="padding:20px 0">
                    <input type='button' name='reporte' value='Ver Reporte' class='boton' id="verReporte"/>
                </center>
            </div>
        </div><!-- container -->
    </body>
</html>