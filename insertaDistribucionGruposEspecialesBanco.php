<?php

require("conexion.inc");
require("estilos_inicio_adm.inc");


$sql_verificacion = "SELECT * from distribucion_banco_muestras where codigo_gestion='$codigo_gestion' and cod_ciclo='$codigo_ciclo'";
$resp_verificacion = mysql_query($sql_verificacion);
$num_filas_verificacion = mysql_num_rows($resp_verificacion);

if ($num_filas_verificacion == 0) {
    $sqlCiudades = "SELECT c.cod_ciudad, c.descripcion from ciudades c where c.cod_ciudad not in (115)";
    $respCiudades = mysql_query($sqlCiudades);
    while ($datCiudades = mysql_fetch_array($respCiudades)) {
        $codCiudad = $datCiudades[0];
		
        $sqlBanco = "SELECT m.cod_ciudad, bm.cod_med , bmc.codigo_muestra , bmc.cantidad, bmc.cod_visitador ,bm.codigo_linea 
		from banco_muestras bm, medicos m, banco_muestra_cantidad_visitador bmc 
		where m.cod_med = bm.cod_med and bm.cod_med = bmc.cod_medico and bm.id=bmc.id_for 
		and m.cod_ciudad = $codCiudad and bm.ciclo_inicio <= $codigo_ciclo and bm.ciclo_final >=$codigo_ciclo and bm.gestion='$codigo_gestion' 
		and bm.estado=1";
        
		echo $sqlBanco."<br />";
        $respBanco = mysql_query($sqlBanco);
        while ($datBanco = mysql_fetch_array($respBanco)) {
            $codTerritorioBanco = $datBanco[0];
            $codMedBanco = $datBanco[1];
            $codProdBanco = $datBanco[2];
            $cantBanco = $datBanco[3];
            $codVisitadorBanco = $datBanco[4];
            $codLineaBanco = $datBanco[5];

            //VERIFICAMOS LA DISTRIBUCION
            $sqlVeriDist = "SELECT * from distribucion_banco_muestras where codigo_gestion='$codigo_gestion' and cod_ciclo='$codigo_ciclo' and cod_visitador='$codVisitadorBanco' and codigo_producto='$codProdBanco'and grupo_salida=1 and cod_med='$codMedBanco'";
//            echo $sqlVeriDist.";<br />";
            $respVeriDist = mysql_query($sqlVeriDist);
            $filasVeriDist = mysql_num_rows($respVeriDist);
            if ($filasVeriDist == 0) {
                $sqlActProductos = "insert into distribucion_banco_muestras values('$codigo_gestion',
				'$codigo_ciclo','$codTerritorioBanco','$codLineaBanco','$codVisitadorBanco','$codProdBanco','$codMedBanco','$cantBanco',
				0,'1',0)";

                echo "insertaBanco: " . $sqlActProductos."<br />";

                $respActProductos = mysql_query($sqlActProductos);
            } else {
                $sqlActProductos = "update distribucion_banco_muestras set cantidad_planificada=cantidad_planificada+$cantBanco
				where codigo_gestion='$codigo_gestion' and cod_ciclo='$codigo_ciclo' and codigo_linea='$codLineaBanco'
				and cod_visitador='$codVisitadorBanco' and codigo_producto='$codProdBanco' and grupo_salida=2 and cod_med='$codMedBanco'";

                echo "insertaBanco: " . $sqlActProductos."<br />";

                $respActProductos = mysql_query($sqlActProductos);
            }
        }
    }
}
echo "<script language='JavaScript'>
		location.href='registroDistribucionBanco.php?global_linea=$codLineaBanco&gestion=$codigo_gestion&ciclo=$codigo_ciclo';
	</script>";
?>