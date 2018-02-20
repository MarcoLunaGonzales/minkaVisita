<?php
$tipo = $_REQUEST['tipo'];
$fecha_inicio = $_REQUEST['f_inicio'];
$fecha_final = $_REQUEST['f_final'];
//print_r($_COOKIE);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <title></title>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
        <style type="text/css">
            .resul  th { color: #000; font-size: 14px}
            .button { padding: 0.4em 1em; cursor: pointer; text-align: center; background: url("images/ui-bg_glass_80_d7ebf9_1x400.png") repeat-x ; margin: 3px 5px 0; text-decoration: none !important; border: 1px solid #A1C2EB ; line-height: 30px; color: #000; -webkit-border-radius: 6px;-moz-border-radius: 6px;border-radius: 6px; font-size: 12px; color: #3300FF}
            .button:hover { opacity: 0.60 }
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

                $("#baco").click(function(){
                    $.getJSON("baco/index.php",{
                        "fecha_inicio": "<?php echo $fecha_inicio ?>",
                        "fecha_final":  "<?php echo $fecha_final ?>"
                    },response);
    
                    return false;
                })
            })
            function response(datos){
                alert(datos)
            }
        </script>   
    </head>
    <body>
        <?php
        set_time_limit(0);
        require("conexion.inc");
        require("estilos_reportes_central.inc");

        if ($tipo == 1) {
            $tipo_f = "Ingreso";
        } else {
            $tipo_f = "Salida";
        }
        ?>
        <center>
            <table class='textotit'>
                <tr>
                    <th>
                        Reporte Ingreso/Salida Material de Apoyo <br />
                    </th>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
        <?php
//        if ($tipo == 1) {
        $sql_ingreso = "SELECT i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado FROM ingreso_almacenes i, tipos_ingreso ti where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='1000' and i.grupo_ingreso=2 and i.fecha <= '$fecha_final' and i.fecha >= '$fecha_inicio' order by i.nro_correlativo desc";
//        } else {
        $sql_salida = "SELECT s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='1000' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida=2 and s.fecha <= '$fecha_final' and s.fecha >= '$fecha_inicio' order by s.nro_correlativo desc ";
//        }
        $resp_sql_ingreso = mysql_query($sql_ingreso);
        $resp_sql_salida = mysql_query($sql_salida);
        ?>
        <center>
            <table class="resul" border="1">
                <tr>
                    <th>&numero;</th>
                    <th>Codigo</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre <?php echo $tipo_f ?> </th>
                    <th>Observaciones</th>
                    <th>Nota entrega</th>
                    <th>&numero; Correlativo</th>
                    <th><?php echo $tipo_f ?> anulado</th>
                </tr>
                <?php
                $csv_hdr = "No, Codigo, Fecha, Hora, Nombre " . $tipo_f . ", Observaciones, Nota entrega, No correlativo, " . $tipo_f . " anulado ";

                $count = 1;
                while ($row = mysql_fetch_array($resp_sql_ingreso)) {
                    $codigo = $row[0];
                    $fecha = $row[1];
                    $hora = $row[2];
                    $nombre = $row[3];
                    $obs = $row[4];
                    $nota = $row[5];
                    $n_correlativo = $row[6];
                    $anulado = $row[7];
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $codigo; ?></td>
                        <td><?php echo $fecha ?></td>
                        <td><?php echo $hora ?></td>
                        <td><?php echo $nombre ?></td>
                        <td><?php echo $obs ?></td>
                        <td><?php echo $nota ?></td>
                        <td><?php echo $n_correlativo ?></td>
                        <td><?php echo $anulado ?></td>
                    </tr>
                    <?php
                    $csv_output .= $count . ", " . $codigo . ", " . $fecha . ", " . $hora . ", " . $nombre . ", " . $obs . ", " . $nota . ", " . $n_correlativo . ", " . $anulado . "\n";
                    $count++;
                }

                $count = $count;
                while ($row = mysql_fetch_array($resp_sql_salida)) {
                    $codigo = $row[0];
                    $fecha = $row[1];
                    $hora = $row[2];
                    $nombre = $row[3];
                    $obs = $row[4];
                    $nota = $row[5];
                    $n_correlativo = $row[6];
                    $anulado = $row[7];
                    ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $codigo; ?></td>
                        <td><?php echo $fecha ?></td>
                        <td><?php echo $hora ?></td>
                        <td><?php echo $nombre ?></td>
                        <td><?php echo $obs ?></td>
                        <td><?php echo $nota ?></td>
                        <td><?php echo $n_correlativo ?></td>
                        <td><?php echo $anulado ?></td>
                    </tr>
                    <?php
                    $csv_output .= $count . ", " . $codigo . ", " . $fecha . ", " . $hora . ", " . $nombre . ", " . $obs . ", " . $nota . ", " . $n_correlativo . ", " . $anulado . "\n";
                    $count++;
                }
                ?>
            </table>
        </center>
        <br />
        <center>
            <table border='0'>
                <tr>
                    <td>
                        <a href='javascript:window.print();'><IMG border='no' alt='Imprimir esta' src='imagenes/print.gif'>Imprimir</a>
                    </td>
                </tr>
                <tr>
                    <form name="export" action="export.php" method="post">
                        <input type="submit" value="Exportar a CSV" class="button" />
<!--                        <input type="hidden" value="<?php echo $csv_hdr; ?>" name="csv_hdr" />
                        <input type="hidden" value="<?php echo $csv_output; ?>" name="csv_output" />
                        <input type="hidden" value="<?php echo $tipo_f; ?>" name="csv_tipo" />-->
                        <input type="hidden" value="<?php echo $fecha_inicio; ?>" name="fecha_ini" />
                        <input type="hidden" value="<?php echo $fecha_final; ?>" name="fecha_fin" />
                    </form>
                    <!--                    <a href="#" class="button" style="padding: 8px 16px" id="baco">Exportar a Baco</a>-->
                </tr>
            </table>
            <div class="modal"></div>
    </body>
</html>