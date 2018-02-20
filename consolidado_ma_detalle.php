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
$nombres_productos = substr($nombres_productos, 0, -1);
$nombres_productos_explode = explode(",", $nombres_productos);

$productos_combinado = array_combine($productos_lista_explode, $nombres_productos_explode);
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
        <div class="row">
            <div class="twelve columns centered">
                <table border="1" class="aa">
                    <tr>
                        <th>Producto</th>
                        <th>Asociados</th>
						<th>Agencias</th>
                        <th>&nbsp;</th>
                        <th>Total Planificado</th>
                        <th>Existencia Guardada</th>
                        <th>Existencia Actuales</th>
                    </tr>
                    <?php
                    foreach($productos_combinado as $codigo_prod => $nom_prod){ 
					
                        $txt_lineas="SELECT DISTINCT (select l.nombre_linea from lineas l where l.codigo_linea=ad.linea_mkt),
						ad.linea_mkt, ad.espe_linea  from asignacion_ma_excel a, asignacion_ma_excel_detalle ad 
						where ad.id_asignacion_ma = a.id and a.ciclo = $ciclo_final 
						and a.gestion = $gestion_final and ad.codigo_ma = '$codigo_prod' order by 1";
						$sql_lineas = mysql_query($txt_lineas);
                        
						$txt_p="SELECT DISTINCT a.codigo_mm, CONCAT(m.descripcion,' ',m.presentacion), a.ciudades  from asignacion_ma_excel a, 
							asignacion_ma_excel_detalle ad, muestras_medicas m where ad.id_asignacion_ma = a.id and m.codigo = a.codigo_mm 
							and a.ciclo = $ciclo_final and a.gestion = $gestion_final and ad.codigo_ma = '$codigo_prod' order by 1";
						$sql_p = mysql_query($txt_p);
                        
						?>
                        <tr>
                            <td><?php echo $nom_prod . " - " . $codigo_prod; ?></td>
                            <td>
                                <?php 
                                $pro = "";
								$codigoMMRep="";
                                while ($row_p = mysql_fetch_array($sql_p)) {
                                    echo $row_p[1]."<br />";
									$codigoMMRep=$row_p[0];
									$codCiudades=$row_p[2];
                                }
								
								
                                ?>
                            </td>
							<td>
								<?php
								$sqlCiu="select descripcion from ciudades where cod_ciudad in ($codCiudades) order by 1";
								$respCiu=mysql_query($sqlCiu);
								$nombreCiudades="";
								while($datCiu=mysql_fetch_array($respCiu)){
									$nombreCiudades=$nombreCiudades." ".$datCiu[0];
								}
								echo $nombreCiudades;
								?>
							</td>
                            <td>
                                <table border="1">
                                    <tr>
                                        <th>Especialidad</th>
                                        <th>L&iacute;nea</th>
                                        <th>Plan.A</th>
                                        <th>M&eacute;d.A</th>
                                        <th>Plan.B</th>
                                        <th>M&eacute;d.B</th>
                                        <th>Plan.C</th>
                                        <th>M&eacute;d.C</th>
                                        <th>Total</th>
                                    </tr>
                                    <?php 
                                    $sum_total = 0; 
                                    // $codigos_funcionarios = '';
                                    while($row_lineas = mysql_fetch_array($sql_lineas)){ 
                                        $sum_total_parcial = 0;
                                        $especialidad_linea = $row_lineas[2];
										$nombreLineaMkt=$row_lineas[0];
										$codLineaMkt=$row_lineas[1];
                                        /*$especialidad_linea = explode(" ", $especialidad_linea);
                                        $especialidad = $especialidad_linea[0]; 
                                        $linea = $especialidad_linea[1]; */
                                        
										$txt_cant="SELECT sum(dist_a), sum(dist_b), sum(dist_c) from asignacion_ma_excel a, 
										asignacion_ma_excel_detalle ad where a.id = ad.id_asignacion_ma and a.ciclo = $ciclo_final 
										and a.gestion = $gestion_final and ad.codigo_ma = $codigo_prod and ad.espe_linea = '$especialidad_linea' 
										and ad.linea_mkt='$codLineaMkt'";
										
										$sql_cantidades = mysql_query($txt_cant);
                                        
										$sum_dist_a = mysql_result($sql_cantidades, 0, 0);
                                        $sum_dist_b = mysql_result($sql_cantidades, 0, 1);
                                        $sum_dist_c = mysql_result($sql_cantidades, 0, 2);

                                        /* Aqui va la parte para sacar la cantidad de medicos y la cantidad de muestras */

                                        $ciudades_finales = '';
                                        $lineas_finales = '';
                                        $re = '';
                                        $var = $linea;
                                        $ultimo_valor_max = $var[strlen($var) - 1];
                                        if($especialidad == "ONC"){
                                            $especialidad = "ONCO";
                                        }
										
                                        
										
										$sqlTxtCant="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
										rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
										AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and rmd.categoria_med = 'A' 
										and rmd.cod_especialidad = '$especialidad_linea' and rmc.codigo_linea in ($codLineaMkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
										and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$codigoMMRep'  
										and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador
										and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($codCiudades))";
																			
										//echo $sqlTxtCant;
										$sql_cantidad_a = mysql_query($sqlTxtCant);
                                        $cantidad_final_a = mysql_result($sql_cantidad_a, 0,0);
                                        if($cantidad_final_a == '' or $cantidad_final_a == 0){$cantidad_final_a = 0;}
                                        
										$sqlTxtCant="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
										rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
										AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final 
										and rmd.categoria_med = 'B' and rmd.cod_especialidad = '$especialidad_linea' and rmc.codigo_linea in ($codLineaMkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
										and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$codigoMMRep' 
										and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador
										and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($codCiudades))";
										$sql_cantidad_b = mysql_query($sqlTxtCant);
                                        $cantidad_final_b = mysql_result($sql_cantidad_b, 0,0);
                                        if($cantidad_final_b == '' or $cantidad_final_b == 0){$cantidad_final_b = 0;}
										
                                        $sqlTxtCant="SELECT COUNT(DISTINCT (rmd.cod_med)) FROM rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, 
										rutero_maestro_detalle_aprobado rmd, medicos m, parrilla_personalizada p WHERE rmc.cod_rutero = rm.cod_rutero AND rm.cod_contacto = rmd.cod_contacto 
										AND m.cod_med = rmd.cod_med and rmc.codigo_gestion = $gestion_final and rmc.codigo_ciclo = $ciclo_final and 
										rmd.categoria_med = 'C' 
										and rmd.cod_especialidad = '$especialidad_linea' and rmc.codigo_linea in ($codLineaMkt) and p.cod_gestion=rmc.codigo_gestion and p.cod_ciclo=rmc.codigo_ciclo 
										and p.cod_linea=rmc.codigo_linea and p.cod_med=m.cod_med and p.cod_mm='$codigoMMRep' 
										and rmd.cod_visitador=rmc.cod_visitador and rm.cod_visitador=rmd.cod_visitador and rmd.cod_visitador=rmc.cod_visitador
										and rmc.cod_visitador in (select f.codigo_funcionario from funcionarios f where f.cod_ciudad in ($codCiudades))";
										
										$sql_cantidad_c = mysql_query($sqlTxtCant);
                                        $cantidad_final_c = mysql_result($sql_cantidad_c, 0,0);
                                        if($cantidad_final_c == '' or $cantidad_final_c == 0){$cantidad_final_c = 0;}
                                        if($cantidad_final_a == ''){
                                            $cantidad_final_a = 0;
                                        }
                                        if($especialidad == 'ONCO'){
                                            $especialidad = 'ONC';
                                        }
                                        /* FIN  de la parte para sacar la cantidad de medicos y la cantidad de muestras */

                                        $sum_total_parcial = $sum_total_parcial + ($sum_dist_a*$cantidad_final_a) + ($sum_dist_b*$cantidad_final_b) + ($sum_dist_c*$cantidad_final_c);
                                        ?>
                                        <tr>
                                            <td><?php echo $nombreLineaMkt; ?></td>
                                            <td><?php echo $especialidad_linea; ?></td>
                                            <td><?php echo $sum_dist_a; ?></td>
                                            <td><?php echo $cantidad_final_a; ?></td>
                                            <td><?php echo $sum_dist_b; ?></td>
                                            <td><?php echo $cantidad_final_b; ?></td>
                                            <td><?php echo $sum_dist_c; ?></td>
                                            <td><?php echo $cantidad_final_c; ?></td>
                                            <td style="text-align:center"><strong><?php echo $sum_total_parcial; ?></strong></td>
                                            <?php  
                                            $sum_total = $sum_total + $sum_total_parcial ;
                                            ?>
                                        </tr>
                                        <?php 
                                    } 
                                    ?> 
                                </table>
                            </td>
                            <?php  
                            $sql_fechas_ingresos="SELECT sum(id.cantidad_unitaria) from ingreso_almacenes i, ingreso_detalle_almacenes id where i.cod_ingreso_almacen=id.cod_ingreso_almacen and i.cod_almacen='1000' and i.grupo_ingreso=2 and i.ingreso_anulado=0 and id.cod_material='$codigo_prod'";

                            $resp_fechas_ingresos=mysql_query($sql_fechas_ingresos);
                            $dat_kardex_ingresos=mysql_fetch_array($resp_fechas_ingresos);
                            $cantidad_ingreso_kardex=$dat_kardex_ingresos[0];

                            $sql_fechas_salidas="SELECT sum(sd.cantidad_unitaria) from salida_almacenes s, salida_detalle_almacenes sd where s.cod_salida_almacenes=sd.cod_salida_almacen and s.cod_almacen='1000' and s.grupo_salida=2 and s.salida_anulada=0 and sd.cod_material='$codigo_prod'";

                            $resp_fechas_salidas=mysql_query($sql_fechas_salidas);
                            $dat_kardex_salidas=mysql_fetch_array($resp_fechas_salidas);
                            $cantidad_salida_kardex=$dat_kardex_salidas[0];

                            $existencia_final=$cantidad_ingreso_kardex-$cantidad_salida_kardex;
                            ?>
                            <td>
                                <?php 
                                if($sum_total <= $existencia_final){
                                    echo "<span style='color:green; font-weight:bold'>".$sum_total."</span>";
                                }
                                else{
                                    echo "<span style='color:red; font-weight:bold'>".$sum_total."</span>";
                                } 
                                ?>
                            </td>
                            <td><?php echo $sum_total; ?></td>
                            <td><?php echo $existencia_final; ?></td>
                        </tr>
                        <?php
                    } 
                    ?>
                </table>
            </div>
        </div>
    </div>
    <div class="modal"></div>
</body>
</html>