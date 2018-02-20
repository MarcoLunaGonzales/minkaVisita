<?php
require("conexion.inc");
require("estilos_gerencia.inc");


$indice_tabla = 1;
?>

<!DOCTYPE HTML>
<html lang="en-US">
    <head>
        <meta charset="UTF-8">
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <title></title>
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
                
                $("#replicar").click(function(){
                    var ciclo_destino = $("#destino").val()
                    var ciclo_origen = $("#origen").val()
                    var gestion= $("#gestion").val()
                    $.getJSON("lib/recuperar/recuperarTodo.php",{
                        "destino" : ciclo_destino,
                        "origen" : ciclo_origen,
                        "gestion" : gestion
                    },response);
    
                    return false;
                })
                function response(datos){
                    alert(datos)
                    location.href = 'navegador_lineasvisitafuncionario.php?ciclo=<?php echo $ciclo_destino; ?>&cod_linea_vis=<?php echo $cod_linea_vis; ?>&codigo_gestion=<?php echo $gestion; ?>';
                }
            })
        </script>
    </head>
    <body>
    <center>
        <header>
            <h3>Ciclo a Replicarse <?php echo $ciclo_origen; ?> al ciclo <?php echo $ciclo_destino; ?></h3>
            <h4>Todas Las Lineas</h4>
        </header>
        <section id="body">
            <?php
            $sql = "select codigo_l_visita, nombre_l_visita from lineas_visita where codigo_linea='1021' and codigo_l_visita <> 0 order by nombre_l_visita";
            $resp = mysql_query($sql);
            while ($row_l = mysql_fetch_array($resp)) {
                ?>
                <h2><?php echo $row_l[1]; ?></h2>
                <?php
                $codigo_linea_v = $row_l[0];
                $sql_func = "select f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion 
                        from funcionarios f, lineas_visita_visitadores fl, ciudades c
                        where f.cod_cargo=1011 and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario 
                        and f.cod_ciudad=c.cod_ciudad and fl.codigo_l_visita='$codigo_linea_v' and fl.codigo_ciclo = $ciclo_origen and codigo_gestion = $gestion order by c.descripcion, f.paterno";
                $resp_func = mysql_query($sql_func);
                ?>
                <table border='1' class='texto' cellspacing='0' width='30%'>
                    <tr>
                        <th>&nbsp;</th>
                        <th>Territorio</th>
                        <th>Visitador</th>
                    </tr>
                    <?php
                    while ($dat_func = mysql_fetch_array($resp_func)) {
                        $codigo_vis = $dat_func[0];
                        $nombre_completo = "$dat_func[1] $dat_func[2] $dat_func[3]";
                        $nombreCiudad = $dat_func[4];
                        ?>
                        <tr>
                            <td><?php echo $indice_tabla; ?></td>
                            <td><?php echo $nombreCiudad; ?></td>
                            <td><?php echo $nombre_completo; ?></td>
                        </tr>
                        <?php $indice_tabla++; ?>
                    <?php } ?>
                    <?php $indice_tabla = 1; ?>
                </table>
            <?php } ?>
            <input type="button" class="boton" value="Replicar" style="margin-top: 10px" id="replicar" />
            <input type="hidden" value="<?php echo $ciclo_destino ?>" id="destino" />
            <input type="hidden" value="<?php echo $ciclo_origen ?>" id="origen" />
            <input type="hidden" value="<?php echo $gestion ?>" id="gestion" />
        </section>
    </center>
    <div class="modal"></div>
</body>
</html>