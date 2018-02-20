<?php require("conexion.inc"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" href="stilos.css" type="text/css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="ajax/gruposespeciales/send.data.js"></script>
        <script type="text/javascript">
            var windowSizeArray = [ "width=1000,height=700,scrollbars=yes" ];
            $(document).ready(function() {
                $("#territorio, #linea").change(function(){
                    gruposEspeciales();
                })
                $("#verReporte").click(function(){
                    if($("#gruposespeciales").val() == null || $("#gruposespeciales").val() == ''){
                        alert("Debe seleccionar al menos un grupo especial.")
                    }else{
                        
                        var cod_ge = $("#gruposespeciales").val();
                        var territorio  = $("#territorio").val();
                        var linea  = $("#linea").val();
                        var ciclo  = $("#ciclo_de").val();
                        var url = "rpt_central_grupos_especiales.php?cod_ge="+cod_ge+"&territorio="+territorio+"&linea="+linea+"&ciclo="+ciclo;
                        var windowName = "Reporte_Medicos_grupos_especiales";
                        var windowSize = windowSizeArray[0];
                    
                        window.open(url, windowName, windowSize);
                    }
                })  
            });
        </script>
        <!--style type="text/css">
            #header h2 { color: #5F7BA9; font-size: 11pt; font-family: Verdana}
            body { background: #fff url('imagenes/fondo_pagina.jpg') no-repeat right; }
            #filtro th { text-align: left;; width: 30%; font-size: 12px }
        </style-->
	<?php
		require("estilos_gerencia.inc");
	?>
    </head>
    <body>
        <div id="container">
            <div id="header">
                <center>
                    <h1>Grupos Especiales</h1>
                </center>
            </div>
            <div id="content">
                <center>
                    <table class="texto">
                        <tr>
                            <th>Ciclo:</th>
                            <td>
                                <select name="ciclo_de" id="ciclo_de">
                                    <?php
                                    $sql_gestion = mysql_query("select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,15");
                                    while ($dat = mysql_fetch_array($sql_gestion)) {
                                        $codCiclo = $dat[0];
                                        $codGestion = $dat[1];
                                        $nombreGestion = $dat[2];
                                        ?>
                                        <option value="<?php echo $codCiclo . "|" . $codGestion ?>"><?php echo $codCiclo . " " . $nombreGestion ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>L&iacute;nea</th>
                            <td>
                                <select name="linea" id="linea">
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
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio" multiple>
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
                            <th>Grupos especiales</th>
                            <td>
                                <select name="gruposespeciales" id="gruposespeciales" size="10" multiple>

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