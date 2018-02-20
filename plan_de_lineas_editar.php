<?php
error_reporting(0);
require("conexion.inc");
$year = date('Y');
$id = $_GET['id'];
?>
<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="iso-8859-1">
        <title>Plan de Lineas Editar</title>
        <link type="text/css" href="css/style.css" rel="stylesheet" />
        <link rel="stylesheet" href="css/calendar.css" type="text/css" />
        <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
        <link rel="stylesheet" href="responsive/stylesheets/style.css">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <script type="text/javascript" src="lib/funciones3.js"></script>
        <script type="text/javascript" src="lib/dandd.js"></script>
        <!--<script src="http://www.google.com/jsapi" type="text/javascript"></script>-->
        <script type="text/javascript">//
            //            google.load("jqueryui", "1.7.2");
            //        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                countChecked();
                $(".eliminar_ciudad").live('click',function(){
                    $(this).parent().parent().remove()
                })
                var cadena = [];
                $( "input.nom_ciudades" ).on( "click", countChecked  );
                $("#agregar").on( "click", grilla  );
                
                $("#siguiente").click(function(){

                    var id_cab = $(this).attr('rel')

                    $("#frame .cajita").each(function(index){
                        
                        cadena.push($(".cajita_left input",this).val());
                        $(".cajita_right span", this).each(function(){
                            cadena.push($(this).text());
                        })
                        cadena.push("@");
                    })
                    armarGrilla2(cadena,id_cab)
                })
                $(".eliminar_espe").click(function(){
                    $(this).parent().remove()
                })
            });
        </script>
        <style type="text/css">
            #contenido{
                padding: 40px 10px;
                width: 99%;
            }
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
            .ciudades {
                border: 2px solid  #999;
                background: #eee;
                float: left;
                margin: 3px;
                padding: 5px;
                display: block;
                overflow: hidden;
                color: #000;
            }
            .cajita {
                margin: 10px;
                background: #eee;
                border: 1px solid #999;
                display: block;
                overflow: hidden;
                position: relative;
            }
            .cajita_left {
                padding: 50px 10px 0;
                float: left;
                width: 15%;
                height: 130px;
                text-align: center;
                line-height: 22px;
                font-weight: bold;
                text-transform: uppercase;
                position: relative;
            }
            .cajita_right {
                border-left: 10px solid white;
                padding: 10px;
                float: right;
                width: 84%;
                height: 130px;
            }
            #sticky_navigation_wrapper { width:100%; height:110px; pposition: relative; z-index: 99; margin-top: 10px }
            #sticky_navigation { width:100%; height:110px; background:url(images/trans-black-60.png); -moz-box-shadow: 0 0 5px #999; -webkit-box-shadow: 0 0 5px #999; box-shadow: 0 0 5px #999; }
            #sticky_navigation ul { list-style:none; margin:0; padding:5px; }
            #sticky_navigation ul li { margin:0; padding:0; display:inline; }
            #sticky_navigation ul li a { display:block; float:left; margin:7px 0 0 5px; padding:0 7px; height:40px; line-height:40px;  background:#eee;  border: 3px dashed #999; color: #000; ccursor: move }
            #sticky_navigation ul li a:hover, #sticky_navigation ul li a.selected { color:#fff; background:#111; }
            .dragged {
                position: absolute
            }
            #agregar {
                float: right;
                margin: 10px 10px 0 0;
            }
            .espe {
                display:block; 
                float: left;
                margin:7px 10px; 
                padding:0 7px;
                height:40px; 
                line-height:40px;  
                background:#eee;  
                border: 3px dashed #999;
                color:#000;               
            }
            .espe img {
                cursor: pointer;
            }
            .eliminar_ciudad {
                position: absolute;
                top: 0;
                left: 0;
                cursor: pointer;
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
                <div class="row" style="width:100%" id="tab1">
                    <div class="twelve columns">
                        <?php
                        $sql_ciudades = mysql_query("select cod_ciudad, descripcion from ciudades where cod_ciudad <> 115");
                        while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
                            ?>
                            <div class="ciudades"><input type="checkbox" value="<?php echo $row_ciudades[1]; ?>" class="nom_ciudades" name="ciudades" cod="<?php echo $row_ciudades[0] ?>" /><?php echo $row_ciudades[1]; ?></div>
                        <?php } ?>
                    </div>
                    <div id="sticky_navigation_wrapper" class="twelve columns">
                        <div id="sticky_navigation">
                            <div class="demo_container">
                                <ul>
                                    <?php
                                    $sql_lineas = mysql_query("select cod_especialidad from especialidades ORDER BY 1");
                                    while ($row_lineas = mysql_fetch_array($sql_lineas)) {
                                        ?>
                                        <li class="drag" id="drag<?php echo $row_lineas[0] ?>"><a href="javascript:void(0)"><?php echo $row_lineas[0]; ?> <input type="checkbox" value="<?php echo $row_lineas[0]; ?>" /></a></li>
                                    <?php } ?>
                                </ul>
                                <a class="button" href="javascript:void(0)" id="agregar">Agregar</a>
                            </div>
                        </div>
                    </div>
                    <div class="twelve columns caja" id="frame">
                        <?php 
                            $sql_ciudades_editar = mysql_query("SELECT p.ciudad, c.descripcion,p.id from plan_lineas p, ciudades c 
                                where c.cod_ciudad = p.ciudad and p.id_cab = $id");
                            while ($row_ciudad_editar = mysql_fetch_array($sql_ciudades_editar)) {
                        ?>
                        <div class='cajita' id='ciudad_<?php echo $row_ciudad_editar[0] ?>'>
                            <div class='cajita_left'><?php echo $row_ciudad_editar[1] ?> 
                                <img src="imagenes/no.png" alt="Cerrar" class="eliminar_ciudad">
                                <input type='radio' value='<?php echo $row_ciudad_editar[0] ?>' name='marc_ciudades' />
                            </div>
                            <div class='cajita_right'>
                                <?php 
                                    $sql_espe_editar = mysql_query("SELECT DISTINCT especialidad from plan_lineas_detalle pd, plan_lineas p 
                                        where p.id = pd.id and p.id_cab = $id and p.id = $row_ciudad_editar[2] ");
                                    while ($row_espe = mysql_fetch_array($sql_espe_editar)) {
                                ?>
                                        <span class="espe"><?php echo $row_espe[0] ?><img src="imagenes/no.png" alt="Cerrar" class="eliminar_espe" /> </span>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>
                    </div>
                    <div class="twelve columns" >
                        <a href="javascript:void(0)" id="siguiente" class="button right" style="margin-top: 10px" rel="<?php echo $id ?>">Siguiente</a>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>