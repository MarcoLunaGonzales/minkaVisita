<?php
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
            })
        </script>   
    </head>
    <body>
        <?php
        set_time_limit(0);
        require("conexion.inc");
        require("estilos_reportes_central.inc");
        ?>
        <br />
        <center>
            <table class='textotit'>
                <tr>
                    <th>
                        Reporte Ingreso Muestras M&eacute;dicas <br />
                    </th>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
        <?php
        $sql_ingreso = "select i.cod_ingreso_almacen, i.fecha, i.hora_ingreso, ti.nombre_tipoingreso, i.observaciones, i.nota_entrega, i.nro_correlativo, i.ingreso_anulado
		FROM ingreso_almacenes i, tipos_ingreso ti
		where i.cod_tipoingreso=ti.cod_tipoingreso and i.cod_almacen='1000' and i.fecha <= '$fecha_final' and i.fecha >= '$fecha_inicio' and i.grupo_ingreso=1 order by nro_correlativo desc";

        $sql_salidas = "select s.cod_salida_almacenes, s.fecha, s.hora_salida, ts.nombre_tiposalida, c.descripcion, a.nombre_almacen, s.observaciones, s.estado_salida, s.nro_correlativo, s.salida_anulada, s.almacen_destino
		FROM salida_almacenes s, tipos_salida ts, ciudades c, almacenes a
		where s.cod_tiposalida=ts.cod_tiposalida and s.cod_almacen='1000' and s.fecha <= '$fecha_final' and s.fecha >= '$fecha_inicio' and c.cod_ciudad=s.territorio_destino and a.cod_almacen=s.cod_almacen and s.grupo_salida=1 order by s.nro_correlativo desc";

//        echo $sql_ingreso.";"."<br /> ".$sql_salidas.";";

        $ingreso = mysql_query($sql_ingreso);
        ?>
        <br />
        <center>
            <table class="resul" border="1" widh="80%">
                <tr>
                    <th>&numero;</th>
                    <th>Codigo Ingreso</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre tipo Ingreso </th>
                    <th>Observaciones</th>
                    <th>Nota entrega</th>
                    <th>&numero; Correlativo</th>
                    <th>Ingreso anulado</th>
                </tr>
                <?php
                $csv_hdr = "No, Codigo, Fecha, Hora, Nombre " . $tipo_f . ", Observaciones, Nota entrega, No correlativo, " . $tipo_f . " anulado ";
                $count_ingreso = 1;
                while ($row_ingreso = mysql_fetch_array($ingreso)) {
                    $codigo_ingreso_almacen = $row_ingreso[0];
                    $fecha_ingreso = $row_ingreso[1];
                    $hora_ingreso = $row_ingreso[2];
                    $nombre_tipo_ingreso = $row_ingreso[3];
                    $observaciones_ingreso = $row_ingreso[4];
                    $nota_entrega_ingreso = $row_ingreso[5];
                    $nro_correlativo_ingreso = $row_ingreso[6];
                    $ingreso_anulado = $row_ingreso[7];
                    ?>
                    <tr>
                        <td><?php echo $count_ingreso; ?></td>
                        <td><?php echo $codigo_ingreso_almacen; ?></td>
                        <td><?php echo $fecha_ingreso ?></td>
                        <td><?php echo $hora_ingreso ?></td>
                        <td><?php echo $nombre_tipo_ingreso ?></td>
                        <td><?php echo $observaciones_ingreso ?></td>
                        <td><?php echo $nota_entrega_ingreso ?></td>
                        <td><?php echo $nro_correlativo_ingreso ?></td>
                        <td><?php echo $ingreso_anulado ?></td>
                    </tr>
                    <?php
                    $csv_output .= $count_ingreso . ", " . $codigo_ingreso_almacen . ", " . $fecha_ingreso . ", " . $hora_ingreso . ", " . $nombre_tipo_ingreso . ", " . $observaciones_ingreso . ", " . $nota_entrega_ingreso . ", " . $nro_correlativo_ingreso . ", " . $ingreso_anulado . "\n";
                    $count_ingreso = $count_ingreso + 1;
                }
                ?>
            </table>
        </center>
        <br />
        <center>
            <table class='textotit'>
                <tr>
                    <th>
                        Reporte Salida Muestras M&eacute;dicas <br />
                    </th>
                </tr>
                <tr>
                    <td>

                    </td>
                </tr>
            </table>
        </center>
        <br />
        <?php $salidas = mysql_query($sql_salidas); ?>
        <center>
            <table class="resul" border="1" widh="80%">
                <tr>
                    <th>&numero;</th>
                    <th>Codigo Salida</th>
                    <th>Fecha</th>
                    <th>Hora</th>
                    <th>Nombre tipo salida </th>
                    <th>Descripcion</th>
                    <th>Nombre Almacen</th>
                    <th>Observaciones</th>
                    <th>Estado Salida</th>
                    <th>&numero; Correlativo</th>
                    <th>Salida Anulada</th>
                    <th>Almacen destino</th>
                </tr>
                <?php
                $csv_hdr = "No, Codigo, Fecha, Hora, Nombre " . $tipo_f . ", Observaciones, Nota entrega, No correlativo, " . $tipo_f . " anulado ";
                $count_salidas = 1;
                while ($row_salida = mysql_fetch_array($salidas)) {
                    $codigo_salida_almacen = $row_salida[0];
                    $fecha_salida = $row_salida[1];
                    $hora_salida = $row_salida[2];
                    $nombre_tipo_salida_salida = $row_salida[3];
                    $descripcion_salida = $row_salida[4];
                    $nombre_almacen_salida = $row_salida[5];
                    $observaciones_salida = $row_salida[6];
                    $estado_salida = $row_salida[7];
                    $nro_correlativo_salida = $row_salida[8];
                    $salida_anulada = $row_salida[9];
                    $almacen_destino_salida = $row_salida[10];
                    ?>
                    <tr>
                        <td><?php echo $count_salidas; ?></td>
                        <td><?php echo $codigo_salida_almacen; ?></td>
                        <td><?php echo $fecha_salida ?></td>
                        <td><?php echo $hora_salida ?></td>
                        <td><?php echo $nombre_tipo_salida_salida ?></td>
                        <td><?php echo $descripcion_salida ?></td>
                        <td><?php echo $nombre_almacen_salida ?></td>
                        <td><?php echo $observaciones_salida ?></td>
                        <td><?php echo $estado_salida ?></td>
                        <td><?php echo $nro_correlativo_salida ?></td>
                        <td><?php echo $salida_anulada ?></td>
                        <td><?php echo $almacen_destino_salida ?></td>
                    </tr>
                    <?php
                    $csv_output .= $count_salidas . ", " . $codigo_salida_almacen . ", " . $fecha_salida . ", " . $hora_salida . ", " . $nombre_tipo_salida_salida . ", " . $descripcion_salida . ", " . $nombre_almacen_salida . ", " . $observaciones_salida . ", " . $estado_salida . ", " . $nro_correlativo_salida . ", " . $salida_anulada . ", " . $almacen_destino_salida . "\n";
                    $count_salidas++;
                }
                ?>
            </table>
        </center>
        <br />
        <center>
            <table border='0'>
                <tr>
                    <td>
                        <a href='javascript:window.print();'><img border='no' alt='Imprimir esta' src='imagenes/print.gif' />Imprimir</a>
                    </td>
                </tr>
                <tr>
                    <form name="export" action="baco/excel_mm.php" method="post">
                        <input type="submit" value="Exportar a Excel" class="button" id="excel" />
                        <input type="hidden" value="<?php echo $fecha_inicio; ?>" name="fecha_ini" />
                        <input type="hidden" value="<?php echo $fecha_final; ?>" name="fecha_fin" />
                    </form>
                </tr>
            </table>
            <div class="modal"></div>
    </body>
</html>