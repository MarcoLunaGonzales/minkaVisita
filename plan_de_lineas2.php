<?php
error_reporting(0);
require("conexion.inc");
$cadena = $_GET['cadena'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Plan de Lienas</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="lib/freetile/jquery.freetile.js"></script>
        <script type="text/javascript" src="lib/freetile/init.js"></script>
        <script type="text/javascript">
            $(document).ready(function() {
                var cadena = '';
                $('#enviar').click(function(){
                    $("#contenido input.textos, #contenido input:checked").each(function(){
                        cadena += $(this).val()+","
                    })
                    $.getJSON("ajax/plan_lineas/guardar.php",{
                        "cadena" : cadena
                    },response);
    
                    return false;
                })
                function response(datos){
                    alert(datos)
                    window.location.href = "plan_de_lineas_detalle.php";
                }
            });
        </script>
        <style type="text/css">
            #contenido {
                width: 95%;
                padding: 40px 20px;
            }
            p.title {
                font-weight: bold;
            }
            table tbody tr:nth-child(2n){
                background: #e5e4e4
            }
            table tr th {
                padding: 5px 10px
            }
            table tr td  {
                padding: 5px 10px
            }
            table tr td ul  {
                margin-top: 15px
            }
            table tr td ul li {
                list-style: none
            }
            #boton {
                position: fixed;
                right: 10px;
                top: 50px;
                background: #fff;
                border: 1px solid activeborder;
                padding: 2px;
                border-radius: 5px 5px;
            }
            #boton:hover {
                zoom: 1;
                filter: alpha(opacity=60);
                opacity: 0.6;
                background: #29677e;
                border: 1px solid activeborder;
                cursor: pointer;
                color: white !important;
            }
            #boton:hover span {
                color: white;
            }
            .button {
                padding: 10px 10px 11px
            }
        </style>
    </head>
    <body>
        <div id="container">
            <?php require("estilos2.inc"); ?>
            <header id="titulo" style="min-height: 50px">
                <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">Plan de L&iacute;neas</h3>
            </header>
            <div id="contenido">
                <div class="row" id="tab2">
                    <h4 style="text-align: left; font-size: 14px;">L&iacute;neas por Especialidad</h4>
                </div>
                <div class="row" id="grilla_especial">
                    <?php
                    $cadena_sub = substr($cadena, 0, -2);
                    $cadena_replace = str_replace(',@,', '@', $cadena_sub);
                    $cadena_explode = explode("@", $cadena_replace);
                    foreach ($cadena_explode as $datos) {
                        $datos_explode = explode(",", $datos);
                        ?>
                        <div class="three columns end">
                            <p class="title">
                                <?php
                                $sql_ciudad = mysql_query("select descripcion from ciudades where cod_ciudad = $datos_explode[0]");
                                echo (mysql_result($sql_ciudad, 0, 0));
                                ?>
                                <input type="hidden" value="<?php echo "@" . $datos_explode[0]; ?>" class="textos" />
                            </p>
                            <table border="1">
                                <?php
                                array_shift($datos_explode);
                                foreach ($datos_explode as $especialidades) {
                                    ?>
                                    <tr>
                                        <th><?php echo $especialidades; ?> </th>
                                        <td>
                                            <ul class="lista_espe">
                                                <?php
                                                $sql_lineas = mysql_query("select lv.codigo_l_visita,lv.nom_orden from lineas_visita lv, especialidades e, lineas_visita_especialidad lve 
                                                    where lv.codigo_l_visita = lve.codigo_l_visita and lve.cod_especialidad = e.cod_especialidad and e.cod_especialidad = '$especialidades' ORDER BY 2 ");
                                                $tiene_lineas = mysql_num_rows($sql_lineas);
                                                if ($tiene_lineas == 0) {
                                                    ?>
                                                    <li><p>No tiene lineas de visita <input type="hidden" value="<?php echo $especialidades; ?>" class="textos" /><input type="hidden" value="0" class="textos"></p></li>
                                                    <?php
                                                } else {
                                                    while ($row_li = mysql_fetch_array($sql_lineas)) {
                                                        ?>
                                                        <li> <input type="checkbox" value="<?php echo $especialidades.",".$row_li[0]; ?>"  /> <?php echo $row_li[1]; ?></li>
                                                        <?php
                                                    }
                                                }
                                                ?>
                                            </ul>
                                        </td>
                                    </tr>
                                <?php } ?>
                            </table>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <div id="boton">
            <a href="javascript:void(0)" id="enviar" class="button">
                <span> G </span>  
                <span> U </span>  
                <span> A </span>  
                <span> R </span>  
                <span> D </span>  
                <span> A </span>  
                <span> R </span>  
            </a>
        </div>
    </body>
</html>