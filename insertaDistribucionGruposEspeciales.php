<?php

require("conexion.inc");
require("estilos_inicio_adm.inc");

$gestionDistribucion = $global_gestion_distribucion;
$cicloDistribucion = $global_ciclo_distribucion;
$sql_gestion = mysql_query("select codigo_gestion from gestiones where estado = 'Activo' ");
$gestion = mysql_result($sql_gestion, 0, 0);
$sql_verificacion = "select * from distribucion_grupos_especiales where codigo_gestion='$global_gestion_distribucion'
and cod_ciclo='$global_ciclo_distribucion'";
$resp_verificacion = mysql_query($sql_verificacion);
$num_filas_verificacion = mysql_num_rows($resp_verificacion);

if ($num_filas_verificacion == 0) {
    //$sqlCiudades="select c.`cod_ciudad`, c.`descripcion` from `ciudades` c where c.`cod_ciudad` not in (115)";
    $sqlCiudades = "select c.`cod_ciudad`, c.`descripcion` from `ciudades` c where c.`cod_ciudad` in (114)";
    $respCiudades = mysql_query($sqlCiudades);
    while ($datCiudades = mysql_fetch_array($respCiudades)) {
        $codCiudad = $datCiudades[0];

        $sqlGruposDetalle = "select g.`codigo_grupo_especial`, g.`nombre_grupo_especial`, gd.cod_med, cl.`cod_especialidad`, cl.`categoria_med`, g.agencia, g.codigo_linea
			from `grupo_especial` g, `grupo_especial_detalle` gd, `categorias_lineas` cl
			where g.`codigo_grupo_especial`=gd.`codigo_grupo_especial` and 
			gd.`cod_med`=`cl`.`cod_med` and g.`codigo_linea`=cl.`codigo_linea` and g.`agencia`=$codCiudad 
			";


        echo $sqlGruposDetalle;

        $respGruposDetalle = mysql_query($sqlGruposDetalle);
        while ($datGruposDetalle = mysql_fetch_array($respGruposDetalle)) {
            $codGrupo = $datGruposDetalle[0];
            $nombreGrupo = $datGruposDetalle[1];
            $codMed = $datGruposDetalle[2];
            $codEspe = $datGruposDetalle[3];
            $codCat = $datGruposDetalle[4];
            $codTerritorio = $datGruposDetalle[5];
            $codigoLinea = $datGruposDetalle[6];

            $codVisitador = 0;
            //sacamos el visitador asociado al medico

            $sqlLineaVisita = "select count(*) from `lineas_visita` lv, `lineas_visita_especialidad` lve 
                                       where lv.`codigo_l_visita`=lve.`codigo_l_visita` and lve.`cod_especialidad`='$codEspe' and lv.codigo_linea='$codigoLinea'
                                       ";

            echo "linea visita: $sqlLineaVisita<br>";

            $respLineaVisita = mysql_query($sqlLineaVisita);
            $numLineaVisita = mysql_result($respLineaVisita, 0, 0);
            if ($numLineaVisita == 0) {
                $sqlVisitador = "select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
				`rutero_maestro_detalle_aprobado` rd where 
				rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
				rc.`codigo_ciclo`='$cicloDistribucion' and rc.`codigo_gestion`='$gestionDistribucion' and 
				rd.`cod_med`='$codMed' and rc.codigo_linea='$codigoLinea'";

                echo "LineaVsi 0 $sqlVisitador<br>";

                $respVisitador = mysql_query($sqlVisitador);
                $numFilasVisitador = mysql_num_rows($respVisitador);
                if ($numFilasVisitador > 0) {
                    $codVisitador = mysql_result($respVisitador, 0, 0);
                }
            } else {
                $sqlVisitador = "select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
				`rutero_maestro_detalle_aprobado` rd where 
				rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
				rc.`codigo_ciclo`='$cicloDistribucion' and rc.`codigo_gestion`='$gestionDistribucion' and 
				rd.`cod_med`='$codMed' and rc.codigo_linea='$codigoLinea'";
                $respVisitador = mysql_query($sqlVisitador);

                echo "LineaVsi  $sqlVisitador<br>";

                while ($datVisitador = mysql_fetch_array($respVisitador)) {
                    $codigoVis = $datVisitador[0];
                    $sqlLinVisita = "select gl.`cod_l_visita` from `grupo_especial` g, `grupoespecial_lineavisita` gl, lineas_visita_especialidad le 
						where g.`codigo_grupo_especial`=gl.`cod_grupo` and g.`codigo_grupo_especial`='$codGrupo' and g.codigo_linea='$codigoLinea'
						and le.codigo_l_visita=gl.cod_l_visita and le.cod_especialidad='$codEspe'";

                    echo "sqlLinVisita $sqlLinVisita<br>";

                    $respLinVisita = mysql_query($sqlLinVisita);
                    while ($datLinVisita = mysql_fetch_array($respLinVisita)) {
                        $codigoLineaVisitaGrupo = $datLinVisita[0];
                        //verificamos que el visitador este en la linea de visita
                        $sqlVeriFuncionario = "select count(*) from `lineas_visita_visitadores` l where l.`codigo_funcionario`='$codigoVis' 
						and l.`codigo_l_visita`='$codigoLineaVisitaGrupo' and l.codigo_ciclo = $global_ciclo_distribucion and l.codigo_gestion = $gestion";

                        echo "Veri Func $sqlVeriFuncionario<br>";

                        $respVeriFuncionario = mysql_query($sqlVeriFuncionario);
                        $numFilasVeriFuncionario = mysql_result($respVeriFuncionario, 0, 0);
                        if ($numFilasVeriFuncionario == 1) {
                            $codVisitador = $codigoVis;
                        }
                    }
                }
            }
            echo "<br>$nombreGrupo $codMed $codEspe $codCat Visitador: $codVisitador $codigoLinea<br>";

            //AQUI REALIZAMOS LA DISTRIBUCION PARA LOS MEDICOS QUE TENGAN VISITADOR ASIGNADO
            if ($codVisitador != 0) {

                //DISTRIBUIMOS LAS MUESTRAS MEDICAS
                $sqlParrilla = "select pd.`codigo_muestra`,  SUM(pd.`cantidad_muestra`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
				where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo 
				and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` and p.codigo_linea='$codigoLinea' group by pd.`codigo_muestra`";

                echo $sqlParrilla;

                $respParrilla = mysql_query($sqlParrilla);
                while ($datParrilla = mysql_fetch_array($respParrilla)) {
                    $codProducto = $datParrilla[0];
                    $cantProducto = $datParrilla[1];

                    $sqlVeriDist = "select * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion' and
					cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto' 
					and codigo_linea='$codigoLinea' and grupo_salida=1 and cod_med='$codMed'";
//                    echo "VERIDIST: ".$sqlVeriDist;
                    $respVeriDist = mysql_query($sqlVeriDist);
                    $filasVeriDist = mysql_num_rows($respVeriDist);
                    if ($filasVeriDist == 0) {
                        $sqlActProductos = "insert into distribucion_grupos_especiales values('$gestionDistribucion',
						'$cicloDistribucion','$codTerritorio','$codigoLinea','$codVisitador','$codProducto','$codMed','$cantProducto',
						0,'1',0, 0)";

                        echo $sqlActProductos;

                        $respActProductos = mysql_query($sqlActProductos);
                    } else {
                        $sqlActProductos = "update distribucion_grupos_especiales set cantidad_planificada=cantidad_planificada+$cantProducto 
						where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$codigoLinea'
						and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and cod_med='$codMed' and grupo_salida=1";

                        echo $sqlActProductos;

                        $respActProductos = mysql_query($sqlActProductos);
                    }
                }
                //DISTRIBUIMOS EL MATERIAL DE APOYO
                $sqlParrilla = "select pd.`codigo_material`,  SUM(pd.`cantidad_material`) from `parrilla_especial` p, `parrilla_detalle_especial` pd
				where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo 
				and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` and p.codigo_linea='$codigoLinea'
				group by pd.`codigo_material`";

                echo "<br><br><br>";
                //echo $sqlParrilla;

                $respParrilla = mysql_query($sqlParrilla);
                while ($datParrilla = mysql_fetch_array($respParrilla)) {
                    $codProducto = $datParrilla[0];
                    $cantProducto = $datParrilla[1];

                    $sqlVeriDist = "select * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion' and
					cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto'
					and grupo_salida=2 and cod_med='$codMed'";
                    $respVeriDist = mysql_query($sqlVeriDist);
                    $filasVeriDist = mysql_num_rows($respVeriDist);
                    if ($filasVeriDist == 0) {
                        $sqlActProductos = "insert into distribucion_grupos_especiales values('$gestionDistribucion',
						'$cicloDistribucion','$codTerritorio','$codigoLinea','$codVisitador','$codProducto','$codMed','$cantProducto',
						0,'2',0,0)";
                        $respActProductos = mysql_query($sqlActProductos);
                    } else {
                        $sqlActProductos = "update distribucion_grupos_especiales set cantidad_planificada=cantidad_planificada+$cantProducto 
						where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$codigoLinea'
						and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2 and cod_med='$codMed'";
                        $respActProductos = mysql_query($sqlActProductos);
                    }
                }
            } else {
                echo "NO TIENE VISITADOR ASIGNADO";
            }
        }


        //REALIZAMOS LA DISTRIBUCION DEL BANCO DE MUESTRAS
//        $sqlBanco = "select m.`cod_ciudad`, m.`cod_med`, bd.`cod_muestra`, bd.`cantidad`, b.`codigo_linea`  
//			from `banco_muestras` b, `banco_muestras_detalle` bd, medicos m 
//			where b.`cod_med`=bd.`cod_med` and b.`cod_med`=m.`cod_med` and m.`cod_ciudad`=$codTerritorio";
//
//        echo $sqlBanco;
//
//        $respBanco = mysql_query($sqlBanco);
//        while ($datBanco = mysql_fetch_array($respBanco)) {
//            $codTerritorioBanco = $datBanco[0];
//            $codMedBanco = $datBanco[1];
//            $codProdBanco = $datBanco[2];
//            $cantBanco = $datBanco[3];
//            $codLineaBanco = $datBanco[4];
//
//
//            //sacamos visitador asignado
//            $sqlVisitador = "select DISTINCT(rd.`cod_visitador`) from `rutero_maestro_cab_aprobado` rc, `rutero_maestro_aprobado` rm,
//				`rutero_maestro_detalle_aprobado` rd where 
//				rc.`cod_rutero`=rm.`cod_rutero` and rm.`cod_contacto`=rd.`cod_contacto` and 
//				rc.`codigo_ciclo`='$cicloDistribucion' and rc.`codigo_gestion`='$gestionDistribucion' and 
//				rd.`cod_med`='$codMedBanco' and rc.codigo_linea='$codLineaBanco'";
//
//            echo "BANCO 0 $sqlVisitador<br>";
//
//            $respVisitador = mysql_query($sqlVisitador);
//            $numFilasVisitador = mysql_num_rows($respVisitador);
//            if ($numFilasVisitador > 0) {
//                $codVisitadorBanco = mysql_result($respVisitador, 0, 0);
//            }
//
//
//            //VERIFICAMOS LA DISTRIBUCION
//            $sqlVeriDist = "select * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion' and
//			cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitadorBanco' and codigo_producto='$codProdBanco'
//			and grupo_salida=1 and cod_med='$codMedBanco'";
//            $respVeriDist = mysql_query($sqlVeriDist);
//            $filasVeriDist = mysql_num_rows($respVeriDist);
//            if ($filasVeriDist == 0) {
//                $sqlActProductos = "insert into distribucion_grupos_especiales values('$gestionDistribucion',
//				'$cicloDistribucion','$codTerritorio','$codLineaBanco','$codVisitadorBanco','$codProdBanco','$codMedBanco','$cantBanco',
//				0,'1',0,'$cantBanco')";
//
//                echo "insertaBanco: " . $sqlActProductos;
//
//                $respActProductos = mysql_query($sqlActProductos);
//            } else {
//                $sqlActProductos = "update distribucion_grupos_especiales set cantidad_planificada=cantidad_planificada+$cantBanco, cantidad_bancomm='$cantBanco'
//				where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$codLineaBanco'
//				and cod_visitador='$codVisitadorBanco' and codigo_producto='$codProdBanco' and grupo_salida=2 and cod_med='$codMedBanco'";
//
//                echo "insertaBanco: " . $sqlActProductos;
//
//                $respActProductos = mysql_query($sqlActProductos);
//            }
//        }
    }
}

//echo "<script language='JavaScript'>
//		location.href='registroDistribucionGrupos.php?global_linea=$global_linea';
//	</script>";
?>