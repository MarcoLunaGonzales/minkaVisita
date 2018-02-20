<?php
require("conexion.inc");
require("estilos_gerencia.inc");

$sql = "select f.codigo_funcionario, f.paterno, f.materno, f.nombres, c.descripcion 
	from funcionarios f, lineas_visita_visitadores fl, ciudades c
	where f.cod_cargo=1011 and f.estado=1 and f.codigo_funcionario=fl.codigo_funcionario 
	and f.cod_ciudad=c.cod_ciudad and fl.codigo_l_visita='$cod_linea_vis' and fl.codigo_ciclo = $ciclo_origen and codigo_gestion = $gestion order by c.descripcion, f.paterno";
$resp = mysql_query($sql);
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
                    var linea = $("#linea").val()
                    var gestion= $("#gestion").val()
                    $.getJSON("lib/recuperar/recuperar.php",{
                        "destino" : ciclo_destino,
                        "origen" : ciclo_origen,
                        "linea" : linea,
                        "gestion" : gestion
                    },response);
    
                    return false;
                })
                function response(datos){
                    alert(datos)
                    location.href = 'navegador_lineasvisitafuncionario.php?ciclo=<?php echo $ciclo_destino; ?>&cod_linea_vis=<?php echo $cod_linea_vis; ?>&gestion=<?php echo $gestion; ?>';
                }
            })
        </script>
    </head>
    <body>
    <center>
        <header>
            <h3>Ciclo a Replicarse <?php echo $ciclo_origen; ?> al ciclo <?php echo $ciclo_destino; ?></h3>
        </header>
        <section id="body">
            <table border='1' class='texto' cellspacing='0' width='30%'>
                <tr>
                    <th>&nbsp;</th>
                    <th>Territorio</th>
                    <th>Visitador</th>
                </tr>
                <?php
                while ($dat = mysql_fetch_array($resp)) {
                    $codigo_vis = $dat[0];
                    $nombre_completo = "$dat[1] $dat[2] $dat[3]";
                    $nombreCiudad = $dat[4];
                    echo "<tr>
                                <td align='center'>$indice_tabla</td>
		<td>$nombreCiudad</td>
		<td>$nombre_completo</td>
                            </tr>";
                    $indice_tabla++;
                }
                ?>
            </table>
            <input type="button" class="boton" value="Replicar" style="margin-top: 10px" id="replicar" />
            <input type="hidden" value="<?php echo $ciclo_destino ?>" id="destino" />
            <input type="hidden" value="<?php echo $ciclo_origen ?>" id="origen" />
            <input type="hidden" value="<?php echo $cod_linea_vis ?>" id="linea" />
            <input type="hidden" value="<?php echo $gestion ?>" id="gestion" />
        </section>
    </center>
    <div class="modal"></div>
</body>
</html>