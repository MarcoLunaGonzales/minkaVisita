<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Parrilla Especial Asignacion</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $("#enviar").click(function(){
                    var ciclo_de,territorios;
                    ciclo_de = $("#ciclo_de").val();
                    territorios = $("#territorio").val();
//                    alert(cuantos);
                    window.location.href = "recalculo_ge_detalle_2.php?ciclo="+ciclo_de+"&territorios="+territorios;
                })        
            });
        </script>
        <style type="text/css">
            h1 {
                font-size: 15px
            }
            input[type="button"] {
                margin: 10px 0;
                cursor: pointer;
                background: #fff;
            }
        </style>
    </head>
    <body>
        <div id="contianer">
            <?php require("estilos2.inc"); ?>
            <header>
                <h1>Recalculo de Grupos Especiales</h1>
            </header>
            <div id="contenido">
                <center>
                    <h4>Seleccione el ciclo y territorio(s) para ver el reporte de asignaci&oacute;n de funcionarios.</h4>
                    <table border="1" style="margin-top:20px">
                        <tr>
                            <th>Ciclo:</th>
                            <td>
                                <select name="ciclo_de" id="ciclo_de">
                                    <?php
                                    $sql_gestion = mysql_query("SELECT DISTINCT g.ciclo, g.gestion, gg.nombre_gestion FROM grupos_especiales g, gestiones gg where g.gestion = gg.codigo_gestion ORDER BY g.gestion DESC, g.ciclo DESC");
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
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio" multiple size="14">
                                    <?php
                                    $sql_territorio = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad <> 115 order by c.descripcion");
                                    while ($dat_t = mysql_fetch_array($sql_territorio)) {
                                        $codigo_ciudad = $dat_t[0];
                                        $nombre_ciudad = $dat_t[1];
                                        ?>
                                        <option value="<?php echo $codigo_ciudad ?>"> <?php echo $nombre_ciudad ?></option>
                                    <?php } ?>
                                </select>
                            </td>
                        </tr>
                    </table>
                    <input type="button" id="enviar" value="Ver" style="margin-top: 25px" />
                </center>
            </div>
        </div>
    </body>
</html>