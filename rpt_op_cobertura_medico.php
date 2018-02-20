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
            var windowSizeArray = [ "width=1000,height=700,scrollbars=yes" ];
            $(document).ready(function() {
                $("#territorio, #gestion, #linea,#especialidad, #categoria").change(function(){
                    datosMedico();
                })
                $("#verReporte").click(function(){
                    if($("#medicos").val() == null || $("#medicos").val() == ''){
                        alert("Debe seleccionar al menos un medico.")
                    }else{
                        var cod_med = $("#medicos").val();
                        var gestion  = $("#gestion").val();
                        var linea  = $("#linea").val();
                        var url = "rpt_coberturamedico.php?codigo_med="+cod_med+"&gestion="+gestion+"&linea="+linea;
                        var windowName = "Reporte Cobertura por medico";
                        var windowSize = windowSizeArray[0];
                    
                        window.open(url, windowName, windowSize);
                    }
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
                    <h2>Cobertura por M&eacute;dico</h2>
                </center>
            </div>
            <div id="content">
                <center>
                    <table border="1" width="30%" cellpadding="5" id="filtro">
                        <tr>
                            <th>Gesti&oacute;n - Ciclo</th>
                            <td>
                                <select name="gestion" id="gestion">
                                    <?php
                                    $sql_gestion = mysql_query(" select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15 ");
                                    while ($row_gestion = mysql_fetch_assoc($sql_gestion)) {
                                        $codigo_ciclo = $row_gestion['cod_ciclo'];
                                        $codigo_gestion = $row_gestion['codigo_gestion'];
                                        $nombre_gestion = $row_gestion['nombre_gestion'];
                                        ?>
                                        <option value="<?php echo $codigo_ciclo."|".$codigo_gestion ;?>"><?php echo $codigo_ciclo . " " . $nombre_gestion; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio">
                                    <option value="vacio">Seleccione una opci&oacute;n</option>
                                    <?php
                                    $sql_territorio = mysql_query("select cod_ciudad, descripcion from ciudades order by descripcion  ASC");
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
                            <th>L&iacute;nea</th>
                            <td>
                                <select name="liena" id="linea">
                                    <option value="vacio">Seleccione una opci&oacute;n</option>
                                    <?php
                                    $sql_linea = mysql_query("select codigo_linea, nombre_linea from lineas where linea_promocion=1 and estado=1 order by nombre_linea");
                                    while ($row_linea = mysql_fetch_assoc($sql_linea)) {
                                        $codigo_linea = $row_linea['codigo_linea'];
                                        $nombre_linea = $row_linea['nombre_linea'];
                                        ?>
                                        <option value="<?php echo $codigo_linea; ?>"><?php echo $nombre_linea; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Especialidad</th>
                            <td>
                                <select name="especialidad" id="especialidad" size="10" multiple>
                                    <?php
                                    $sql_especialidad = mysql_query("select cod_especialidad, desc_especialidad from especialidades order by desc_especialidad");
                                    while ($row_especialidad = mysql_fetch_assoc($sql_especialidad)) {
                                        $codigo_especialidad = $row_especialidad['cod_especialidad'];
                                        ?>
                                        <option value="<?php echo $codigo_especialidad; ?>"><?php echo $codigo_especialidad; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Categor&iacute;a</th>
                            <td>
                                <select name="categoria" id="categoria" size="4" multiple>
                                    <?php
                                    $sql_categoria = mysql_query("select c.`categoria_med` from `categorias_medicos` c where c.`categoria_med`<>'D' order by 1");
                                    while ($row_categoria = mysql_fetch_assoc($sql_categoria)) {
                                        $categoria_medico = $row_categoria['categoria_med'];
                                        ?>
                                        <option value="<?php echo $categoria_medico; ?>"><?php echo $categoria_medico; ?></option>
                                        <?php
                                    }
                                    ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>M&eacute;dicos</th>
                            <td>
                                <select name="medicos" id="medicos" size="10" multiple>
                                </select>
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