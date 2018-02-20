<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="iso-8859-1">
    <title>Duplicar lineas de un ciclo</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <style type="text/css">
    .modal {
        display:    none;
        position:   fixed;
        z-index:    1000;
        top:        0;
        left:       0;
        height:     100%;
        width:      100%;
        background: rgba( 0, 0, 0,.8 ) 
        url('http://i.stack.imgur.com/FhHRx.gif') 
        50% 50% 
        no-repeat;
    }
    /* When the body has the loading class, we turn
    the scrollbar off with overflow:hidden */
    body.loading {
        overflow: hidden;   
    }

            /* Anytime the body has the loading class, our
            modal element will be visible */
            body.loading .modal {
                display: block;
            }
            </style>
            <script type="text/javascript">
            $(document).ready(function(){
                $("body").on({
                    ajaxStart: function() { 
                        $(this).addClass("loading"); 
                    },
                    ajaxStop: function() { 
                        $(this).removeClass("loading"); 
                    }    
                });
                var ciclo_ini,territorios;
                $("#ciclos_inicio,#territorios").change(function(){
                    ciclo_ini = $("#ciclos_inicio").val()
                    territorios = $("#territorios").val()

                    $.getJSON("ajax/select/ciclo.php",{
                        "ciclo_ini" : ciclo_ini,
                        "territorios": territorios
                    },response);

                    return false;
                })
                function response(datos){
                    $("#especialidades").html(datos)
                }
                var ciclo_ini,ciclo_para,territorio_ini,territorio_para,especialidad;
                $("#replicar").click(function(){
                    ciclo_ini = $("#ciclos_inicio").val();
                    territorio_ini = $("#territorios").val();
                    especialidad = $("#especialidades").val();
                    territorio_para = $("#territorios_des").val();
                    ciclo_para = $("#ciclos").val();
                    
                    $.getJSON("ajax/select/replicar_ap.php",{
                        "ciclo_ini" : ciclo_ini,
                        "territorio_ini" : territorio_ini,
                        "especialidad" : especialidad,
                        "territorio_para" : territorio_para,
                        "ciclo_para" : ciclo_para
                    },response2);

                    return false;
                })
                function response2(datos){
                    alert(datos)
                    window.location.href = "asignacion_productos_p2_vista_preliminar.php?territorios="+territorio_para+"&ciclo_gestion="+ciclo_para;
                }
                
            })
</script>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php require("estilos3.inc"); ?>
            <h3>Duplicar Lineas asignacion de territorio</h3>
        </header>
        <section role="main">
            <div class="row" style="margin-bottom:10px">

                <div class="two columns end">
                    Ciclo Inicio
                </div>
                <div class="two columns end">
                    Territorio
                </div>
                <div class="two columns end">
                    Especialidad
                </div>
                <div class="two columns end">
                    Ciclo a
                </div>
            </div>
            <div class="row">

                <div class="two columns end">
                    <?php
                    $sql_ciclos = mysql_query("SELECT DISTINCT a.ciclo, g.codigo_gestion, g.nombre_gestion from asignacion_de_productos a, gestiones g where a.gestion = g.codigo_gestion");
                    ?>
                    <select name="ciclos_inicio" id="ciclos_inicio" size="15">
                        <?php while ($row_ciclos = mysql_fetch_array($sql_ciclos)) { ?>
                        <option value="<?php echo $row_ciclos[0] . "-" . $row_ciclos[1]; ?>"><?php echo $row_ciclos[0] . " - " . $row_ciclos[2]; ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="two columns end">
                    <?php  
                        $sql_territorios = mysql_query("SELECT c.cod_ciudad, c.descripcion from ciudades c, asignacion_de_productos a where a.ciudad = c.cod_ciudad")
                    ?>
                    <select name="territorios" id="territorios" size="15">
                        <?php while ($row_ciudad = mysql_fetch_array($sql_territorios)) { ?>
                        <option value="<?php echo $row_ciudad[0] ?>"><?php echo $row_ciudad[1] ?></option>
                        <?php } ?>     
                    </select>
                </div>

                <div class="two columns end">
                    <select name="especialidades" id="especialidades" size="15" multiple>
                        <option value=""></option>
                    </select>
                </div>

                <div class="two columns end">
                    <?php  
                        $sql_territorios1 = mysql_query("select c.cod_ciudad, c.descripcion from ciudades c where cod_ciudad <> 115 order by 2 ASC")
                    ?>
                    <select name="territorios_des" id="territorios_des" size="15" multiple>
                        <?php while ($row_ciudad1 = mysql_fetch_array($sql_territorios1)) { ?>
                        <option value="<?php echo $row_ciudad1[0] ?>"><?php echo $row_ciudad1[1] ?></option>
                        <?php } ?>     
                    </select>
                </div>

                <div class="two columns end">
                    <?php
                    $sql_cicloss = mysql_query("select a.cod_ciclo, b.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b where a.codigo_gestion = b.codigo_gestion and a.codigo_linea = 1021 and b.codigo_gestion in (1009,1010) ORDER BY codigo_gestion DESC, cod_ciclo DESC limit 12");
                    ?>
                    <select name="ciclos" id="ciclos" size="15">
                        <?php while ($row_ciclos = mysql_fetch_array($sql_cicloss)) { ?>
                        <option value="<?php echo $row_ciclos[0] . "-" . $row_ciclos[1]; ?>"><?php echo $row_ciclos[0] . " - " . $row_ciclos[2]; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top:20px">
                <div class="two columns centered">
                    <a href="javascript:void(0)" id="replicar" class="button">Replicar</a>
                </div>
            </div>
        </section>
        <div class="modal"></div>
    </div>
</body>
</html>