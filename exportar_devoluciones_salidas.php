<?php
set_time_limit(0);
require_once ('conexion.inc');

$valores = $_GET['valores'];
$separados = explode("|", $valores);

$cod_ciclos = $separados[0];
$cod_gestiones = $separados[1];

$sql_gestion = mysql_query(" SELECT nombre_gestion from gestiones where codigo_gestion = $cod_gestiones ");
$nombre_gestion = mysql_result($sql_gestion, 0, 0);

$sql_almacen = mysql_query(" SELECT cod_almacen from almacenes where cod_ciudad = $global_agencia ");
$codigo_almacen = mysql_result($sql_almacen, 0, 0);
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title></title>
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
    #cargar {
        border: 0px inset #00005B #00005B;
        height: 18px;
        width: 118px;
        font-family: Arial, Helvetica, sans-serif;
        font-style: normal;
        font-size: 11px;
        background-image: url(imagenes/boton5.gif);
        color: #FFFFFF;
        border: none;
        cursor: pointer;
        background-repeat:  no-repeat;
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
        $("#cargar").click(function(){
            $.getJSON("ajax/cargaDevolucion/carga.php",{
                "valores" : "<?php echo $valores ?>"
            },response);
        })

        function response(datos){
            if(datos.estado == 'bien'){
                $("#resultados").html(datos.mensaje)
                alert(datos.mensaje)
                $(location).attr('href','navegador_devolucion_almacen.php');
            }
            if(datos.estado == 'mal'){
                $("#resultados").html(datos.mensaje)
                alert(datos.mensaje)
            }
        }
    })
    </script>
</head>
<body background="imagenes/fondo_pagina.jpg">
    <center>
        <h2>Detalle de devoluci&oacute;n a la central</h2>
        <h3>Ciclo: <?php echo $cod_ciclos; ?> | Gestión: <?php echo $nombre_gestion; ?></h3>
    </center>
    <?php
    if ($global_usuario == 1100) {
        $sqlVis = "SELECT d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, funcionarios f where d.codigo_ciclo=$cod_ciclos and d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario and f.cod_ciudad in (122,116,124) and d.estado_devolucion=2 and d.tipo_devolucion = 1";
    } else {
        $sqlVis = "SELECT d.codigo_devolucion, f.codigo_funcionario, f.paterno, f.materno, f.nombres, d.tipo_devolucion from devoluciones_ciclo d, funcionarios f where d.codigo_ciclo=$cod_ciclos and d.codigo_gestion='$cod_gestiones' and d.codigo_visitador=f.codigo_funcionario and f.cod_ciudad='$global_agencia' and d.estado_devolucion=2 and d.tipo_devolucion = 1";
    }
	
	//echo $sqlVis;

    $respVis = mysql_query($sqlVis);

    while ($row_datos = mysql_fetch_array($respVis)) {
        $codigos .= $row_datos[0] . ",";
    }

    $codigos_finales = substr($codigos, 0, -1);
    $sql_detalle_codigos_materiales = mysql_query("SELECT a.codigo_material, CONCAT(b.descripcion, ' ',b.presentacion), SUM(a.cantidad_ing_almacen) from devoluciones_ciclodetalle a, muestras_medicas b where a.codigo_devolucion in ($codigos_finales) and a.cantidad_ing_almacen > 0 and a.codigo_material = b.codigo GROUP BY 1 ORDER BY 2 ;");
    ?>
    <center>
        <table width="70%" border="1">
            <tr>
                <th>Codigo Material</th>
                <th>Nombre Material</th>
                <th>Cantidad A Devolver</th>
                <th>Stock</th>
            </tr>
            <?php 
            while ($row = mysql_fetch_array($sql_detalle_codigos_materiales)) {

                $sql_ingresos = "SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='$codigo_almacen'and id.cod_material='$row[0]' and i.ingreso_anulado=0 and i.grupo_ingreso='1' ";
                $resp_ingresos = mysql_query($sql_ingresos);
                $dat_ingresos = mysql_fetch_array($resp_ingresos);
                $cant_ingresos = $dat_ingresos[0];
                $sql_salidas = "SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='$codigo_almacen'and sd.cod_material='$row[0]' and s.salida_anulada=0 and s.grupo_salida='1' ";
                $resp_salidas = mysql_query($sql_salidas);
                $dat_salidas = mysql_fetch_array($resp_salidas);
                $cant_salidas = $dat_salidas[0];
                $stock2 = $cant_ingresos - $cant_salidas;
                ?>

                <tr>
                    <td><?php echo $row[0]; ?></td>
                    <td><?php echo $row[1]; ?></td>
                    <td><?php echo $row[2]; ?></td>
                    <td>
                        <?php 
                        if ($stock2 >= $row[2]):
                            echo $stock2;
                        else: 
                            echo "<span style='color:red; font-weight:bold'>" . $stock2 . "</span>";
                        endif; 
                        ?>
                    </td>
                </tr>
                <?php
            } 
            ?>
        </table>
    </center>
    <center>
        <div id="resultados">

        </div>
    </center>
    <center>
        <input type="button" name="reporte" rel="0" value="Guardar Devoluci&oacute;n" class="boton" id="cargar">
    </center>
    <div class="modal"></div>
</body>
</html>