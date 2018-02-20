<?php include('../conexion.inc'); ?>
<!DOCTYPE html>

<!-- paulirish.com/2008/conditional-stylesheets-vs-css-hacks-answer-neither/ -->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="es"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="es"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="es"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="es"> <!--<![endif]-->
    <head>
        <meta charset="iso-8859-1" />

        <!-- Set the viewport width to device width for mobile -->
        <meta name="viewport" content="width=device-width" />

        <title>Zonificaci&oacute;n Hermes </title>

        <!-- Included Foundations CSS Files -->
        <link rel="stylesheet" href="stylesheets/foundation.css">
        <link rel="stylesheet" href="stylesheets/offcanvas.css">
        <!-- Included Custom Override CSS Files -->
        <link rel="stylesheet" href="stylesheets/style.css">

        <!--[if lt IE 9]>
          <link rel="stylesheet" href="stylesheets/ie.css">
        <![endif]-->
        <script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false&libraries=places&libraries=drawing&key=AIzaSyAXA8qFVyGWW5ElwlZ44j3dmvYC7mvpBkw"></script>
        <script src="javascripts/modernizr.foundation.js"></script>

        <!-- IE Fix for HTML5 Tags -->
        <!--[if lt IE 9]>
          <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->

    </head>

    <body id="page" class="off-canvas">

        <div class="container">

            <div class="first full-width color-four"> 

                <header id="header" class="row">     

                    <!-- LOGO -->
                    <div class="twelve columns"> 
                        <a href="#" class="logo">
                            <img src="images/hermes.jpg" alt="Hermes" /> 
                            <h1 class="hide-for-small">Zonificaci&oacute;n Hermes</h1>
                        </a> 
                    </div> <!-- end five columns -->

                </header>    
            </div><!-- end full width -->

            <section role="main"> <!-- Main Section - This Is Part Of The Magic -->
                <div class="full-width">
                    <div class="row">
                        <aside>
                            <div class="accordion">
                                <h3>Zonas/Distritos</h3>
                                <div class="content">
                                    <ul id="zonas_distritos">
                                        <?php
                                        $ciudad = mysql_query(" select cod_ciudad,descripcion from ciudades where cod_ciudad <> 115 order by 2 ");
                                        while ($row_ciudades = mysql_fetch_array($ciudad)) {
                                            ?>
                                            <li><a href="#" name="<?php echo $row_ciudades[0] ?>"><?php echo $row_ciudades[1] ?></a></li>
                                            <?php
                                        }
                                        ?>
                                    </ul>
                                    <?php
                                    $distritos = mysql_query("select c.descripcion, d.cod_dist, d.descripcion, c.cod_ciudad FROM ciudades c, distritos d where c.cod_ciudad = d.cod_ciudad");
                                    while ($row = mysql_fetch_array($distritos)) {
                                        ?>
                                        <p class="<?php echo $row[3] ?>" style="display:none"><a href="#"><?php echo $row[0] . " " . $row[2] ?></a></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <h3>M&eacute;dicos</h3>
                                <div class="content">
                                    <?php
                                    $medicos = mysql_query("select * from medicos");
                                    while ($row_medicos = mysql_fetch_array($medicos)) {
                                        ?>
                                        <p><?php echo $row_medicos[1] . " " . $row_medicos[2] . " " . $row_medicos[3] ?></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                                <h3>Clientes</h3>
                                <div class="content">
                                    <?php
                                    $medicoss = mysql_query("select * from medicos");
                                    while ($row_medicos = mysql_fetch_array($medicoss)) {
                                        ?>
                                        <p><?php echo $row_medicos[0] . " " . $row_medicos[1] . " " . $row_medicos[2] ?></p>
                                        <?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <textarea name="ciudad_esco" id="ciudad_esco" cols="30" rows="1" style="height:45px; min-height: 45px; display: none"  ></textarea>
                            <textarea name="searchresults" id="searchresults" cols="30" rows="10" readonly="readonly"></textarea>
                            <div id="color-palette"></div>
                            <div>
                                <button id="delete-button">Borrar selecci&oacute;n de el pol&iacute;gono</button>
                            </div>
                            <div>
                                <button id="obtener-coordenadas">Obtener Coordenadas</button>
                            </div>
                            <div>
                                <button id="guardar-bd">Guardar a la base de datos</button>
                            </div>
                        </aside>
                        <div id="map">
                            <div id="geocoder">
                                <form id="geocode" action="javascript:void(0)">
                                    <input id="address" type="textbox" placeholder="Direcci&oacute;n para buscar" />
                                    <input type="submit" value="Ir...">
                                </form>
                            </div>
                            <div id="map_canvas"></div>
                        </div>
                    </div>
                </div>
            </section>
        </div><!-- end container -->

        <!-- Include JS Files -->
        <script src="javascripts/foundation.js"></script>
        <script src="javascripts/app.js"></script>
        <script src="javascripts/jquery.min.js"></script>
        <script src="javascripts/gmap.js"></script>
        <script src="javascripts/coordenadas.js"></script>
        <script type="text/javascript" src="javascripts/jquery.offcanvas.js"></script>
        <!-- Call Slideshow -->
        <script type="text/javascript">
            $(document).ready(function(){
                $("#zonas_distritos li a").click(function(){
                    $(this).parent().parent().parent().find("p").css("display","none")
                    var codigo = $(this).attr("name")
                    $("."+codigo).css("display", "block")
                    $("#ciudad_esco").html($(this).attr('name'))
                })
                $("#guardar-bd").click(function(){
                    var ciudaad = $("#ciudad_esco").val()
                    var coorde = $("#searchresults").val()
                    coordenadas(ciudaad, coorde);
                })
            })
        </script>
    </body>