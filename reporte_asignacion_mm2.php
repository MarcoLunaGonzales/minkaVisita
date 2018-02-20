<?php
error_reporting(0);
set_time_limit(0);
require("conexion.inc");

$ciclo_gestion = $_GET['ciclo_gestion'];
$ciclo_gestion_explode = explode("-", $ciclo_gestion);
$ciclo_final = $ciclo_gestion_explode[0];
$gestion_final = $ciclo_gestion_explode[1];

$productos_lista = $_GET['productos'];
$productos_lista_explode = explode(",", $productos_lista);

$nombres_productos = $_GET['codigos_productos'];
$nombres_productos = substr($nombres_productos, 0, -2);
$nombres_productos_explode = explode(",", $nombres_productos);

$productos_combinado = array_combine($productos_lista_explode, $nombres_productos_explode);
$count = 1;
?>
<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="iso-8859-1">
    <title>Asignacion de Material De Apoyo detalle</title>
    <link type="text/css" href="css/style.css" rel="stylesheet" />
    <link type="text/css" href="responsive/stylesheets/foundation.css" rel="stylesheet" />
    <link rel="stylesheet" href="responsive/stylesheets/style.css">
    <script type="text/javascript" src="lib/jquery-1.7.1.js"></script>
    <script type="text/javascript">
    jQuery(document).ready(function($) {

    });
    </script>
    <style>
    table.aa tr th, table.aa tr td {
        padding: 5px 15px
    }
    </style>
</head>
<body>
    <div id="container">
        <?php require("estilos3.inc"); ?>
        <header id="titulo" style="min-height: 50px">
            <h3 style="color: #5F7BA9; font-size: 1.5em; font-family: Vernada">ANALISIS DE PRODUCTOS X ESPECIALIDAD EN PARRILLA</h3>
            <h3 style="color: #5F7BA9; font-size: 1.1em; font-family: Vernada; font-weight: normal;">Productos: <?php echo $nombres_productos; ?></h3>
        </header>
        <div id="contenido">
            <div class="row">
                <div class="nine columns centered">
                    <table border="1" class="aa">
                        <tr>
                            <th>&nbsp;</th>
                            <th>Producto</th>
                            <th>&nbsp;</th>
                            <th>Total Planificado</th>
                        </tr>
                        <?php foreach($productos_combinado as $codigo_prod => $nom_prod){ ?>
                        <?php 
                        $sql_lineas = mysql_query("SELECT DISTINCT CONCAT(ad.especialidad, ' ', ad.linea)as nombre, ad.especialidad, ad.linea from asignacion_productos_excel a, asignacion_productos_excel_detalle ad where ad.id = a.id and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.producto = '$codigo_prod' order by 1");
                        ?>
                        <tr>
                            <td><?php echo $count; ?></td>
                            <td><?php echo $nom_prod; ?></td>
                            <td>
                                <table border="1">
                                    <tr>
                                        <th>Especialidad</th>
                                        <th>L&iacute;nea</th>
                                        <th>Planificado</th>
                                        <th>Medicos</th>
                                        <th>Contactos</th>
                                        <th>Total</th>
                                    </tr>
                                    <?php $sum_total = 0; ?>
                                    <?php $cantidad_final_medicos = 0; ?>
                                    <?php while($row_lineas = mysql_fetch_array($sql_lineas)) { ?>        
                                    <tr>
                                        <td><?php echo $row_lineas[1]; ?></td>
                                        <td><?php echo $row_lineas[2]; ?></td>
                                        <?php  
                                        $sql_cantidad = mysql_query("SELECT sum(amd.cantidad) from asignacion_mm_excel am, asignacion_mm_excel_detalle amd WHERE am.id = amd.id and amd.producto = '$codigo_prod' and amd.especialidad = '$row_lineas[1]' and amd.linea = '$row_lineas[2]' and am.ciclo = $ciclo_final and am.gestion = $gestion_final");
                                        
                                        ?>
                                        <td><?php echo mysql_result($sql_cantidad, 0, 0); ?></td>
                                        <?php  
                                        /* Aqui va la parte para sacar la cantidad de medicos y la cantidad de muestras */

                                        $ciudades_finales = '';
                                        $var = $row_lineas[2];
                                        $ultimo_valor_max = $var[strlen($var) - 1];
                                        $sql_ciudades = mysql_query("SELECT p.ciudad from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd, lineas_visita lv where pc.id = p.id_cab and p.id = pd.id and pd.linea = lv.codigo_l_visita and pc.id = (select id from plan_linea_cab where estado = 1) and pd.de = $ultimo_valor_max and pd.especialidad = '$row_lineas[1]'");
                                        while ($row_ciudades = mysql_fetch_array($sql_ciudades)) {
                                            $sql_confirmacion = mysql_query("SELECT MAX(pd.de) from plan_linea_cab pc, plan_lineas p, plan_lineas_detalle pd where pc.id = p.id_cab and p.id = pd.id and pc.id = (SELECT id from plan_linea_cab where estado = 1) and pd.especialidad = '$row_lineas[1]' and p.ciudad = $row_ciudades[0]");
                                            $maxde = mysql_result($sql_confirmacion, 0, 0);
                                            if ($maxde == $ultimo_valor_max) {
                                                $ciudades_finales .= $row_ciudades[0] . ",";
                                            }
                                        }
                                        $ciudades_finales = substr($ciudades_finales, 0, -1);
                                        $sql_codigo_l_visita = mysql_query("SELECT codigo_l_visita from lineas_visita_nom_generio where nom_generico = '$row_lineas[1] $row_lineas[2]'");
                                        $codigo_l_visita = mysql_result($sql_codigo_l_visita, 0, 0);
                                        $sql_visitadores = mysql_query("SELECT DISTINCT lvv.codigo_funcionario from lineas_visita_visitadores lvv, lineas_visita lv, funcionarios f where lv.codigo_l_visita = lvv.codigo_l_visita and f.codigo_funcionario = lvv.codigo_funcionario and lvv.codigo_ciclo = $ciclo_final and lvv.codigo_gestion = $gestion_final and lv.codigo_l_visita = $codigo_l_visita and f.cod_ciudad in ($ciudades_finales)");

                                        while ($row_vi = mysql_fetch_array($sql_visitadores)) {
                                            $codigos_funcionarios .= $row_vi[0].","; 
                                        }
                                        $codigos_funcionarios_final = substr($codigos_funcionarios, 0, -1);

                                        $sql_cantidad_a = mysql_query("SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and rmd.categoria_med in ('A','B','C')and rmd.cod_especialidad = '$row_lineas[1]' and rmd.cod_visitador in ($codigos_funcionarios_final) and m.cod_ciudad in ($ciudades_finales)");
                                        $cantidad_final_a = mysql_result($sql_cantidad_a, 0,0);
                                        if($cantidad_final_a == ''){
                                            $cantidad_final_a = 0;
                                        }

                                        
                                        $sql_cant_contactos = "SELECT DISTINCT rmd.cod_med from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m where rmc.cod_rutero = rm.cod_rutero and rm.cod_contacto = rmd.cod_contacto and m.cod_med = rmd.cod_med and rmc.cod_visitador = rm.cod_visitador and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and rmd.cod_especialidad = '$row_lineas[1]'";
                                        $resp_cant_contactos = mysql_query($sql_cant_contactos);
                                        $num_contactos = mysql_num_rows($resp_cant_contactos);
                                        /* FIN  de la parte para sacar la cantidad de medicos y la cantidad de muestras */

                                        $sum_total = $sum_total + (mysql_result($sql_cantidad, 0, 0)*$cantidad_final_a);
                                        ?>
                                        <td><?php echo $cantidad_final_a; ?></td>
                                        <?php  
                                        $cantidad_final_medicos = $cantidad_final_medicos + $cantidad_final_a;
                                        ?>
                                        <td><?php echo $num_contactos; ?></td>
                                        <td><?php echo (mysql_result($sql_cantidad, 0, 0)*$cantidad_final_a); ?></td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <th>Total</th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                        <th><?php echo $cantidad_final_medicos; ?></th>
                                        <th>&nbsp;</th>
                                        <th>&nbsp;</th>
                                    </tr>
                                </table>
                            </td>
                            <?php  
                            $sql_fechas_ingresos="SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and i.grupo_ingreso=1 and i.ingreso_anulado=0 and id.cod_material='$codigo_prod'";

                            $resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
                            $dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);
                            $cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

                            $sql_fechas_salidas="SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000' and s.grupo_salida=1 and s.salida_anulada=0 and sd.cod_material='$codigo_prod'";

                            $resp_fechas_salidas=mysql_query($sql_fechas_salidas);
                            $dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);
                            $cantidad_salida_kardex=$dat_kardex_salidas[0];

                            $existencia_final=$cantidad_ingreso_kardex-$cantidad_salida_kardex;
                            ?>
                            <td><?php echo $sum_total; ?></td>
                        </tr>
                        <?php 
                        $count++; 
                    } 
                    ?>
                </table>
            </div>
        </div>
    </div>
</div>
<div class="modal"></div>
</body>
</html>