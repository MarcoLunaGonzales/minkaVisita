<?php
set_time_limit(0);
require("conexion.inc");
require("estilos_inicio_adm.inc");

$sql_verificacion_ciclo = mysql_query("SELECT ciclo,gestion  from grupos_especiales where ciclo = $global_ciclo_distribucion and gestion = $global_gestion_distribucion ");
if (mysql_num_rows($sql_verificacion_ciclo) > 0) {
    $cicloDistribucion = mysql_result($sql_verificacion_ciclo, 0, 0);
    $gestionDistribucion = mysql_result($sql_verificacion_ciclo, 0, 1);
} else {
    echo "<script language='JavaScript'>
                  alert('No hay ciclo asociado para la distribucion')
		location.href='navegadorDistribucionGruposCiclos.php';
	</script>";
}

$sql_verificacion = "SELECT * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion'and cod_ciclo='$cicloDistribucion'";
$resp_verificacion = mysql_query($sql_verificacion);
$num_filas_verificacion = mysql_num_rows($resp_verificacion);

if ($num_filas_verificacion == 0) {
    $sqlCiudades = "SELECT c.`cod_ciudad`, c.`descripcion` from `ciudades` c where c.`cod_ciudad` not in (115)";
    $respCiudades = mysql_query($sqlCiudades);
    while ($datCiudades = mysql_fetch_array($respCiudades)) {
        $codCiudad = $datCiudades[0];

        $sqlGruposDetalle = "SELECT g.codigo_grupo_especial,ge.nombre_grupo_especial,gd.cod_med,gd.cod_visitador,cl.cod_especialidad,cl.categoria_med,
		g.territorio, ge.codigo_linea, IFNULL(ge.codigo_linea1,0), IFNULL(ge.codigo_linea2,0), IFNULL(ge.codigo_linea3,0) 
		from grupos_especiales g, grupo_especial ge, grupos_especiales_detalle gd, 
		categorias_lineas cl, grupo_especial_detalle gdd where g.codigo_grupo_especial = ge.codigo_grupo_especial and g.id = gd.id and 
		gd.cod_med = cl.cod_med and ge.codigo_linea = cl.codigo_linea and g.territorio = $codCiudad and g.ciclo = $cicloDistribucion and 
		g.gestion = $gestionDistribucion and gd.cod_visitador in (select f.codigo_funcionario from funcionarios f where estado=1) and
		gd.cod_med=gdd.cod_med and gdd.codigo_grupo_especial=ge.codigo_grupo_especial";
	
		//echo $sqlGruposDetalle;
		
        $respGruposDetalle = mysql_query($sqlGruposDetalle);
        while ($datGruposDetalle = mysql_fetch_array($respGruposDetalle)) {
            $codGrupo = $datGruposDetalle[0];
            $nombreGrupo = $datGruposDetalle[1];
            $codMed = $datGruposDetalle[2];
            $codVisitador = $datGruposDetalle[3];
            $codEspe = $datGruposDetalle[4];
            $codCat = $datGruposDetalle[5];
            $codTerritorio = $datGruposDetalle[6];
            $codigoLinea = $datGruposDetalle[7];
			$codigoLinea1=$datGruposDetalle[8];
			$codigoLinea2=$datGruposDetalle[9];
			$codigoLinea3=$datGruposDetalle[10];
			

            $sqlParrilla = "SELECT pd.`codigo_muestra`,  SUM(pd.`cantidad_muestra`) from `parrilla_especial` p, 
			`parrilla_detalle_especial` pd where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and 
			p.`codigo_grupo_especial` = $codGrupo and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` and 
			p.codigo_linea in ('$codigoLinea','$codigoLinea1','$codigoLinea2','$codigoLinea3') 
			group by pd.`codigo_muestra`";
			
			//echo $sqlParrilla;
			
            $respParrilla = mysql_query($sqlParrilla);
            while ($datParrilla = mysql_fetch_array($respParrilla)) {
                $codProducto = $datParrilla[0];
                $cantProducto = $datParrilla[1];

                $sqlVeriDist = "SELECT * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto'and codigo_linea='$codigoLinea' and grupo_salida=1 and cod_med='$codMed'";
                
                $respVeriDist = mysql_query($sqlVeriDist);
                $filasVeriDist = mysql_num_rows($respVeriDist);
                if ($filasVeriDist == 0) {
                    $sqlActProductos = "INSERT into distribucion_grupos_especiales values('$gestionDistribucion', '$cicloDistribucion','$codTerritorio','$codigoLinea','$codVisitador','$codProducto','$codMed','$cantProducto', 0,'1',0,0)";
                    echo $sqlActProductos;
                    $respActProductos = mysql_query($sqlActProductos);
                } else {
                    $sqlActProductos = "UPDATE distribucion_grupos_especiales set cantidad_planificada=cantidad_planificada+$cantProducto where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$codigoLinea'and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and cod_med='$codMed' and grupo_salida=1";
                    echo $sqlActProductos;
                    $respActProductos = mysql_query($sqlActProductos);
                }
            }
            //DISTRIBUIMOS EL MATERIAL DE APOYO
            $sqlParrilla = "SELECT pd.`codigo_material`,  SUM(pd.`cantidad_material`) from `parrilla_especial` p, `parrilla_detalle_especial` pd where p.`cod_ciclo` = $cicloDistribucion and p.`codigo_gestion` = $gestionDistribucion and p.`codigo_grupo_especial` = $codGrupo and p.`codigo_parrilla_especial`=pd.`codigo_parrilla_especial` and p.codigo_linea='$codigoLinea'group by pd.`codigo_material`";

            echo "<br><br><br>";

            $respParrilla = mysql_query($sqlParrilla);
            while ($datParrilla = mysql_fetch_array($respParrilla)) {
                $codProducto = $datParrilla[0];
                $cantProducto = $datParrilla[1];

                $sqlVeriDist = "SELECT * from distribucion_grupos_especiales where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and cod_visitador='$codVisitador' and codigo_producto='$codProducto'and grupo_salida=2 and cod_med='$codMed'";
                $respVeriDist = mysql_query($sqlVeriDist);
                $filasVeriDist = mysql_num_rows($respVeriDist);
                if ($filasVeriDist == 0) {
                    $sqlActProductos = "INSERT into distribucion_grupos_especiales values('$gestionDistribucion', '$cicloDistribucion','$codTerritorio','$codigoLinea','$codVisitador','$codProducto','$codMed','$cantProducto', 0,'2',0,0)";
                    $respActProductos = mysql_query($sqlActProductos);
                } else {
                    $sqlActProductos = "UPDATE distribucion_grupos_especiales set cantidad_planificada=cantidad_planificada+$cantProducto where codigo_gestion='$gestionDistribucion' and cod_ciclo='$cicloDistribucion' and codigo_linea='$codigoLinea'and cod_visitador='$codVisitador' and codigo_producto='$codProducto' and grupo_salida=2 and cod_med='$codMed'";
                    $respActProductos = mysql_query($sqlActProductos);
                }
            }
        }
    }
}
echo "<script language='JavaScript'>
		location.href='registroDistribucionGrupos.php?global_linea=$codigoLinea';
	</script>";
?>