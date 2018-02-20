<?php

set_time_limit(0);
require("../../conexion.inc");
$global_linea        = $_REQUEST['global_linea_distribucion'];
$gestionDistribucion = $global_gestion_distribucion;
$cicloDistribucion   = $global_ciclo_distribucion;

$sql_gestion = mysql_query("SELECT codigo_gestion from gestiones where estado = 'Activo' ");
$gestion     = mysql_result($sql_gestion, 0,0);

$sql_verificacion = "SELECT * from distribucion_productos_visitadores where codigo_gestion='$global_gestion_distribucion'and cod_ciclo='$global_ciclo_distribucion' and codigo_linea = $global_linea and grupo_salida=1";
$resp_verificacion = mysql_query($sql_verificacion);
$num_filas_verificacion = mysql_num_rows($resp_verificacion);

if ($num_filas_verificacion == 0) {
    $sql_visitador = "SELECT fl.codigo_funcionario,f.cod_ciudad,f.codigo_lineaclave from funcionarios f, funcionarios_lineas fl where f.codigo_funcionario=fl.codigo_funcionario and fl.codigo_linea='$global_linea' and f.cod_cargo='1011' and f.estado=1 and f.codigo_funcionario in (1204,1288,1126,1049) order by fl.codigo_funcionario";

//	f.cod_cargo='1011' and f.estado=1 and and f.codigo_funcionario in (1265,1073)

    $resp_visitador = mysql_query($sql_visitador);
    while ($dat_visitador = mysql_fetch_array($resp_visitador)) {
        $codVisitador = $dat_visitador[0];
        $codTerritorio = $dat_visitador[1];
        $codLineaClave = $dat_visitador[2];
        $sqlRutero = "SELECT count(DISTINCT(rmd.cod_med)), rmd.cod_especialidad, rmd.categoria_med from rutero_maestro_cab_aprobado rmc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rmd, medicos m where rmc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rmd.cod_contacto and m.cod_med=rmd.cod_med and rmc.estado_aprobado='1' and rmc.codigo_linea='$global_linea' and rmc.codigo_ciclo='$cicloDistribucion'and rmc.codigo_gestion='$gestionDistribucion' and rmc.cod_visitador='$codVisitador' group by rmd.cod_especialidad, rmd.categoria_med";
        // echo $sqlRutero;
//		$sqlRutero = "SELECT * from medicos";
        // echo $sqlRutero."<br />";
        $respRutero = mysql_query($sqlRutero);
        $numFilasRutero = mysql_num_rows($respRutero);
        while ($datRutero = mysql_fetch_array($respRutero)) {
            $cantContactos = $datRutero[0];
            $codEspe = $datRutero[1];
            $codCat = $datRutero[2];

            $lineaVisita = 0;
            // if($global_linea == 1009 or $global_linea == 1022 or $global_linea == 1023){
            //     $global_lineaa = 1021;
            // }else{
            //     $global_lineaa = $global_linea;
            // }
            $sqlverificaLineas = "SELECT l.codigo_l_visita from lineas_visita l, lineas_visita_especialidad le, lineas_visita_visitadores_copy lv where l.codigo_l_visita=le.codigo_l_visita and l.codigo_l_visita=lv.codigo_l_visita and le.codigo_l_visita=lv.codigo_l_visita and lv.codigo_linea_visita='1021' and lv.codigo_funcionario='$codVisitador' and le.cod_especialidad='$codEspe' and lv.codigo_ciclo = $cicloDistribucion and lv.codigo_gestion = $gestionDistribucion";
        // echo $sqlverificaLineas."<br />";
            $respVerificaLineas = mysql_query($sqlverificaLineas);
            $numVerificaLineas = mysql_num_rows($respVerificaLineas);
            if ($numVerificaLineas > 0) {
                $datVerificaLineas = mysql_fetch_array($respVerificaLineas);
                $lineaVisita = $datVerificaLineas[0];
            }
            if($lineaVisita == 0){

            }else{

                $sqlParrilla = "SELECT pd.codigo_muestra, (sum(pd.cantidad_muestra)*$cantContactos) from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'and p.codigo_gestion='$gestionDistribucion'and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita'and p.agencia='$codTerritorio' group BY(pd.codigo_muestra)";
                $respParrilla = mysql_query($sqlParrilla);
                $numFilasParrilla = mysql_num_rows($respParrilla);

                while ($datParrilla = mysql_fetch_array($respParrilla)) {
                    $codProducto = $datParrilla[0];
                    $cantProducto = $datParrilla[1];

                    $sqlVeriDist = "SELECT * from distribucion_productos_visitadores where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and codigo_linea='$global_linea'and grupo_salida=1";
                    $respVeriDist = mysql_query($sqlVeriDist);
                    $filasVeriDist = mysql_num_rows($respVeriDist);
                    if ($filasVeriDist == 0) {
                        $sqlActProductos = "INSERT into distribucion_productos_visitadores values('$gestionDistribucion','$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto',0,'1',0)"; 
                        // echo $sqlActProductos;
                        $respActProductos = mysql_query($sqlActProductos);
                    } else {
                        $sqlActProductos = "UPDATE distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantProducto where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=1";
                        $respActProductos = mysql_query($sqlActProductos);
                    }
                }

                $sqlParrilla = "SELECT pd.codigo_material, (sum(pd.cantidad_material)*$cantContactos) from parrilla p, parrilla_detalle pd where p.codigo_parrilla=pd.codigo_parrilla and p.codigo_linea='$global_linea' and p.cod_ciclo='$cicloDistribucion'and p.codigo_gestion='$gestionDistribucion'and p.cod_especialidad='$codEspe' and p.categoria_med='$codCat' and p.codigo_l_visita='$lineaVisita' and p.agencia='$codTerritorio' group BY(pd.codigo_material)";
            // echo $sqlParrilla."<br />";
                $respParrilla = mysql_query($sqlParrilla);
                $numFilasParrilla = mysql_num_rows($respParrilla);

                while ($datParrilla = mysql_fetch_array($respParrilla)) {
                    $codProducto = $datParrilla[0];
                    $cantProducto = $datParrilla[1];

                    $sqlVeriDist = "SELECT * from distribucion_productos_visitadores where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and  codigo_linea='$global_linea'and grupo_salida=2";
                    $respVeriDist = mysql_query($sqlVeriDist);
                    $filasVeriDist = mysql_num_rows($respVeriDist);
                    if ($filasVeriDist == 0) {
                        $sqlActProductos = "INSERT into distribucion_productos_visitadores values('$gestionDistribucion', '$cicloDistribucion','$codTerritorio','$global_linea','$codVisitador','$codProducto','$cantProducto', 0,'2',0)";
                    // echo $sqlActProductos . "<br />";
                        $respActProductos = mysql_query($sqlActProductos);
                    } else {
                        $sqlActProductos = "UPDATE distribucion_productos_visitadores set cantidad_planificada=cantidad_planificada+$cantProducto where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$global_linea'and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2";
                    // echo $sqlActProductos . "<br />";
                        $respActProductos = mysql_query($sqlActProductos);
                    }
                }
            }
        }
    }

//    echo "Error message = " . mysql_error() . 'The error code: ' . mysql_errno() . 'state: ' . mysql_sqlstate();

    echo json_encode($global_linea);
} else {
    echo json_encode($global_linea);
}
/**/
?>