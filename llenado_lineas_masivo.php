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
                var ciudades,especialidades,ciclo;
                
                function response(datos){
                    $("#funcionarios").html(datos)
                }
                var ciclo_de,ciclo_para,funcionarios;
                $("#replicar").click(function(){
                    especialidades = $("#especialidades").val();
                    funcionarios = $("#funcionarios").val();
                    ciclos = $("#ciclos").val();
                    // alert(ciclos);
                    $.getJSON("ajax/llenado_masivo/llenado_masivo.php",{
                        "especialidades" : especialidades,
                        "funcionarios" : funcionarios,
                        "ciclos" : ciclos
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
            <?php require("estilos3.inc"); ?>
            <h3>Registrar Lineas de visita x Territorio</h3>
        </header>
        <section role="main">
            <div class="row" style="margin-bottom:10px">
               
                <div class="two columns end">
                    Especialidad
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
                    $sql_especialidades = mysql_query('select codigo_l_visita, nom_orden from lineas_visita where codigo_l_visita <> 0 order by 2');
                    ?>
                    <select name="especialidades" id="especialidades" size="15" multiple>
                        <?php while ($row_espe = mysql_fetch_array($sql_especialidades)) { ?>
                        <option value="<?php echo $row_espe[0]; ?>"><?php echo $row_espe[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="four columns end">
                    <?php
                    $sql_funcionarios = mysql_query("select f.codigo_funcionario, CONCAT(f.paterno,' ', f.materno,' ', f.nombres) as nom from funcionarios f where f.cod_cargo=1011 and f.codigo_funcionario in(select l.codigo_funcionario from lineas_visita_visitadores l inner join ciclos c on l.codigo_ciclo=c.cod_ciclo and c.estado='Activo' inner join gestiones g on g.codigo_gestion=l.codigo_gestion and g.estado='Activo') order by 2");
                    ?>
                    <select name="funcionarios" id="funcionarios" size="15" multiple>
                        <?php while ($row_funcionarios = mysql_fetch_array($sql_funcionarios)) { ?>
                        <option value="<?php echo $row_funcionarios[0]; ?>"><?php echo $row_funcionarios[1]; ?></option>
                        <?php } ?>
                    </select>
                </div>
               
                <div class="two columns end">
                        <?php
                        $sql_cicloss = mysql_query("select a.cod_ciclo, b.codigo_gestion,b.nombre_gestion from ciclos a, gestiones b where a.codigo_gestion = b.codigo_gestion and a.codigo_linea = 1032 and b.codigo_gestion in (1011) ORDER BY codigo_gestion DESC, cod_ciclo DESC limit 12");
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
                        <a href="javascript:void(0)" id="replicar" class="button">Guardar</a>
                    </div>
                </div>
            </section>
            <div class="modal"></div>
        </div>
    </body>
    </html>