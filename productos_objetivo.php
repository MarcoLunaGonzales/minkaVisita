<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Banco De Muestras</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="lib/cal.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                $('input.one').simpleDatepicker({ startdate: <?php echo $year ?>, enddate: 2020 });
                $('.desdee').hide();
                $('.escogerr').hide();
                $('.aaa').live('change',function(){
                    var valor = $(this).val();
                    if(valor == 0){
                        $('.desdee').show();
                        $('.escogerr').hide();
                    }else{
                        $('.desdee').hide();
                        $('.escogerr').show();
                    }
                })
                $('#cuales').live('change',function(){
                    var cuantoss,cuanto;
                    $('option:selected',this).each(function(index){
                        cuantoss = 0;
                        cuantoss =  parseFloat(cuantoss) + parseFloat(index)
                    })
                    cuanto = $("#cuantos").val();
                    if(cuantoss <= (cuanto-1)){

                    }else{
                        alert("No puede seleccionar mas posiciones que numero de productos.");
                        $('option:selected',this).each(function(index){
                            $(this).removeAttr('selected')
                        })
                    }
                    
                })
                $("#enviar").click(function(){
                    var ciclo_de,fecha_hasta,cuantos,territorios,desde,tipo;
                    tipo = $(".aaa:checked").val();
                    ciclo_de = $("#ciclo_de").val();
                    fecha_hasta = $("#fecha1").val();
                    territorios = $("#territorio").val();
                    cuantos = $("#cuantos").val();
                    desde = $("#desde").val();
                    cuales = $("#cuales").val();
                    if(tipo == 0){
                        window.location.href = "productos_objetivo_detalle_previo.php?ciclo="+ciclo_de+"&fecha="+fecha_hasta+"&territorios="+territorios+"&cuantos="+cuantos+"&desde="+desde+"&tipo=0";
                    }else{
                        window.location.href = "productos_objetivo_detalle_previo.php?ciclo="+ciclo_de+"&fecha="+fecha_hasta+"&territorios="+territorios+"&cuantos="+cuantos+"&desde="+cuales+"&tipo=1";
                    }
                })                
            });
        </script>
        <style type="text/css">
            #contenido tr th {
                padding: 5px
            }
            .controls input, .controls select {
                padding: 0
            }
            input[type="button"] {
                margin: 10px 0;
                cursor: pointer;
                background: #fff;
            }
        </style>
    </head>
    <body>
        <div id="container">
            <?php require("estilos2.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Productos Objetivo</h3>
            </header>
            <div id="contenido">
                <center>
                    <table border="1">
                        <tr>
                            <th>Ciclo de:</th>
                            <td>
                                <select name="ciclo_de" id="ciclo_de">
                                    <?php
                                    $sql_gestion = mysql_query("select distinct(c.cod_ciclo), c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g where c.codigo_gestion=g.codigo_gestion order by g.codigo_gestion DESC, c.cod_ciclo desc limit 0,11");
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
                            <th>Vigente hasta:</th>
                            <td><input class="one" type="text" name="date" value="" id="fecha1" /></td>
                        </tr>
                        <tr>
                            <th>N&uacute;mero de Productos:</th>
                            <td>
                                <select name="cuantos" id="cuantos">
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th></th>
                            <td style="padding: 5px">
                                <input type="radio" value="0" name="aaa" class="aaa">Desde
                                <input type="radio" value="1" name="aaa" class="aaa">Posiciones
                            </td>
                        </tr>
                        <tr class="desdee">
                            <th>Desde:</th>
                            <td>
                                <select name="desde" id="desde">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                </select>
                            </td>
                        </tr>
                        <tr class="escogerr">
                            <th>Posiciones:</th>
                            <td>
                                <select name="cuales" id="cuales" multiple size="10">
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                    <option value="7">7</option>
                                    <option value="8">8</option>
                                    <option value="9">9</option>
                                    <option value="10">10</option>
                                    <option value="11">11</option>
                                    <option value="12">12</option>
                                    <option value="13">13</option>
                                    <option value="14">14</option>
                                    <option value="15">15</option>
                                    <option value="16">16</option>
                                    <option value="17">17</option>
                                    <option value="18">18</option>
                                    <option value="19">19</option>
                                    <option value="20">20</option>
                                </select>
                            </td>
                        </tr>
                        <tr>
                            <th>Territorio</th>
                            <td>
                                <select name="territorio" id="territorio" multiple size="13">
                                    <?php
                                    $sql_territorio = mysql_query("select c.cod_ciudad, c.descripcion from ciudades c order by c.descripcion");
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
                    <input type="button" id="enviar" value="Ver" />
                </center>
            </div>
        </div>
    </body>
</html>