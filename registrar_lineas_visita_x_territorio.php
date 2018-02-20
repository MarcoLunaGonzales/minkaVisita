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
        $("#ciclo, #especialidades, #territorios").change(function(){
            ciudades = $("#territorios").val()
            especialidades = $("#especialidades").val()
            ciclo = $("#ciclo").val()

            $.getJSON("ajax/select/select-chain-fu.php",{
                "ciudades" : ciudades,
                "especialidades" : especialidades,
                "ciclo" : ciclo
            },response);

            return false;
        })
        function response(datos){
            $("#funcionarios").html(datos)
        }
        var ciclo_de,ciclo_para,funcionarios;
        $("#replicar").click(function(){
            ciclo_de = $("#ciclo").val();
            ciclo_para = $("#ciclo_f").val();
            funcionarios = $("#funcionarios").val();

            $.getJSON("ajax/select/replicar.php",{
                "ciclo_de" : ciclo_de,
                "ciclo_para" : ciclo_para,
                "funcionarios" : funcionarios
            },response2);

            return false;
        })
        function response2(datos){
            alert(datos)
        }

    })
</script>
</head>
<body>
    <div id="container">
        <header id="titulo">
            <?php 
            require("estilos3.inc"); 
            ?>
            <h3>Registrar Lineas de visita x Territorio</h3>
        </header>
        <section role="main">
            <div class="row" style="margin-bottom:10px">
                <div class="two columns end">
                    Territorio
                </div>
                <div class="two columns end">
                    Especialidad
                </div>
                <div class="two columns end">
                    Ciclo de
                </div>
                <div class="four columns end">
                    Funcionario
                </div>
                <div class="two columns end">
                    Ciclo a
                </div>
            </div>
            <div class="row">
                <div class="two columns end">
                    <?php
                    $sql_territorio = mysql_query("SELECT cod_ciudad, descripcion from ciudades where cod_ciudad <> 115 order by 2");
                    ?>
                    <select name="territorios" id="territorios" size="15" multiple>
                        <?php
                        while ($row_teritorio = mysql_fetch_array($sql_territorio)) {
                            ?>
                            <option value="<?php echo $row_teritorio[0]; ?>"><?php echo $row_teritorio[1]; ?></option>
                            <?php
                        } 
                        ?>
                    </select>
                </div>
                <div class="two columns end">
                    <?php
                    $sql_especialidades = mysql_query('SELECT codigo_l_visita, nom_orden from lineas_visita where codigo_l_visita <> 0 order by 2');
                    ?>
                    <select name="especialidades" id="especialidades" size="15" multiple>
                        <?php
                        while ($row_espe = mysql_fetch_array($sql_especialidades)) { 
                            ?>
                            <option value="<?php echo $row_espe[0]; ?>"><?php echo $row_espe[1]; ?></option>
                            <?php
                        } 
                        ?>
                    </select>
                </div>
                <div class="two columns end">
                    <?php
                    $sql_ciclos = mysql_query("SELECT DISTINCT l.codigo_ciclo, l.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b ,lineas_visita_visitadores l where l.codigo_gestion = b.codigo_gestion and a.cod_ciclo = l.codigo_ciclo and a.codigo_linea  in (1021, 1032) and b.codigo_gestion in (1011, 1012) ORDER BY 2 DESC, 1 DESC");
                        ?>
                        <select name="ciclo" id="ciclo" size="15">
                            <?php
                            while ($row_ciclo = mysql_fetch_array($sql_ciclos)) { 
                                ?>
                                <option value="<?php echo $row_ciclo[0] . "-" . $row_ciclo[1]; ?>"><?php echo $row_ciclo[0] . " - " . $row_ciclo[2]; ?></option>
                                <?php
                            } 
                            ?>
                        </select>
                    </div>
                    <div class="four columns end">
                        <select name="funcionarios" id="funcionarios" size="15" multiple>
                            <option>[ninguno seleccionado]</option>
                        </select>
                    </div>
                    <div class="two columns end">
                        <?php
                        $sql_cicloss = mysql_query("SELECT DISTINCT a.cod_ciclo, b.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b where a.codigo_gestion = b.codigo_gestion and a.codigo_linea in (1021,1032) and b.codigo_gestion in (1011, 1012) ORDER BY codigo_gestion DESC, cod_ciclo DESC limit 12");
                        ?>
                        <select name="ciclo_f" id="ciclo_f" size="15">
                            <?php while ($row_ciclos = mysql_fetch_array($sql_cicloss)) { ?>
                            <option value="<?php echo $row_ciclos[0] . "-" . $row_ciclos[1]; ?>"><?php echo $row_ciclos[0] . " - " . $row_ciclos[2]; ?></option>
                            <?php
                        } 
                        ?>
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