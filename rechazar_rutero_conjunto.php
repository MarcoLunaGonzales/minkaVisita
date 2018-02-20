<!DOCTYPE HTML>
<html lang="es-US">
<head>
    <meta charset="iso-8859-1">
    <title>Rechazar Ruteros</title>
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
    body.loading {
        overflow: hidden;   
    }
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
        var ciudades,especialidades,ciclo;
        $("#ciclo, #linea, #territorios").change(function(){
            ciudades = $("#territorios").val()
            linea = $("#linea").val()
            ciclo = $("#ciclo").val()
            $.getJSON("ajax/select/funcionarios_x_ciclo_x_territorio.php",{
                "ciudades" : ciudades,
                "linea" : linea,
                "ciclo" : ciclo
            },response);

            return false;
        })
        function response(datos){
            $("#funcionarios").html(datos)
        }
        var ruteros;
        $("#funcionarios").change(function(){
            funcionarios = $("#funcionarios").val()
            ciclo = $("#ciclo").val()
            linea = $("#linea").val()
            $.getJSON("ajax/select/ruteros_x_ciclo_x_funcionarios.php",{
                "funcionarios" : funcionarios,
                "linea" : linea,
                "ciclo" : ciclo
            },response3);

            return false;
        })
        function response3(datos){
            $("#ruteros").html(datos)
        }

        var ciclo_de,ciclo_para,funcionarios;
        $("#replicar").click(function(){
            ruteros = $("#ruteros").val();
            ciclo = $("#ciclo").val();
            linea = $("#linea").val();

            $.getJSON("ajax/rutero/rechazar.php",{
                "ruteros" : ruteros,
                "ciclo" : ciclo,
                "linea" : linea
            },response2);

            return false;
        })
        function response2(datos){
            alert(datos);
            window.location.href = "rechazar_rutero_conjunto.php";
        }

    })
</script>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php require("estilos3.inc"); ?>
            <h3>Rechazar Ruteros</h3>
        </header>
        <section role="main">
            <div class="row" style="margin-bottom:10px">
                <div class="two columns end">
                    Ciclo / Gestion
                </div>
                <div class="two columns end">
                    Territorio
                </div>
                <div class="two columns end">
                    Linea
                </div>
                <div class="three columns end">
                    Funcionario
                </div>
                <div class="three columns end">
                    Rutero a Rechazar
                </div>
            </div>
            <div class="row">
                <div class="two columns end">
                    <?php
                    $sql_ciclos = mysql_query("SELECT DISTINCT c.cod_ciclo, c.codigo_gestion, g.nombre_gestion from ciclos c, gestiones g 
					where g.codigo_gestion = c.codigo_gestion and c.codigo_gestion in (1013, 1014) order by codigo_gestion desc, cod_ciclo desc"); 
                    ?>
                    <select name="ciclo" id="ciclo" size="15">
                        <?php while ($row_ciclo = mysql_fetch_array($sql_ciclos)) { ?>
                        <option value="<?php echo $row_ciclo[0] . "-" . $row_ciclo[1]; ?>"><?php echo $row_ciclo[0] . " / " . $row_ciclo[2]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="two columns end">
                    <?php
                    $sql_territorio = mysql_query("SELECT cod_ciudad, descripcion from ciudades where cod_ciudad <> 115 order by 2");
                    ?>
                    <select name="territorios" id="territorios" size="15" multiple>
                        <?php while ($row_teritorio = mysql_fetch_array($sql_territorio)) { ?>
                        <option value="<?php echo $row_teritorio[0]; ?>"><?php echo $row_teritorio[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="two columns end">
                    <?php
                    $sql_linea = mysql_query("SELECT codigo_linea, nombre_linea from lineas where linea_promocion = 1 and estado  = 1 ORDER BY 2");
                    ?>
                    <select name="linea" id="linea" size="15">
                        <?php while ($row_linea = mysql_fetch_array($sql_linea)) { ?>
                        <option value="<?php echo $row_linea[0]; ?>"><?php echo $row_linea[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="three columns end">
                    <select name="funcionarios" id="funcionarios" size="15" multiple>
                        <option>[ninguno seleccionado]</option>
                    </select>
                </div>
                <div class="three columns end">
                    <select name="ruteros" id="ruteros" size="15" multiple>
                        <option>[ninguno seleccionado]</option>
                    </select>
                </div>
            </div>
            <div class="row" style="margin-top:20px">
                <div class="two columns centered">
                    <a href="javascript:void(0)" id="replicar" class="button">Rechazar</a>
                </div>
            </div>
        </section>
        <div class="modal"></div>
    </div>
</body>
</html>