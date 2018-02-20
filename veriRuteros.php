<?php

require("conexion.inc");

$codVisitador = 1072;
$codLinea = 1021;
$codCiclo = 8;
$codGestion = 1009;

$sql = "select rd.cod_med, rd.cod_especialidad, rd.categoria_med, rd.cod_zona from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm,
	rutero_maestro_detalle_aprobado rd 
	where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and rc.codigo_linea=$codLinea and 
	rc.cod_visitador=$codVisitador and rc.codigo_ciclo=$codCiclo and rc.codigo_gestion=$codGestion";
$resp = mysql_query($sql);

while ($dat = mysql_fetch_array($resp)) {
    $codMed = $dat[0];
    $codEspe = $dat[1];
    $codCat = $dat[2];
    $codZona = $dat[3];

    $sqlVeri1 = "select * from categorias_lineas where cod_med=$codMed and codigo_linea=$codLinea and cod_especialidad='$codEspe' and 
		categoria_med='$codCat'";
    $respVeri1 = mysql_query($sqlVeri1);
    $filasVeri1 = mysql_num_rows($respVeri1);

    if ($filasVeri1 == 0) {
        echo "error en  categorias lineas medico $codMed<br>";
    } else {
        echo "$codMed OK1<br>";
    }


    $sqlVeri2 = "select * from direcciones_medicos where cod_med=$codMed and numero_direccion=$codZona";
    $respVeri2 = mysql_query($sqlVeri2);
    $filasVeri2 = mysql_num_rows($respVeri2);

    if ($filasVeri2 == 0) {
        echo "error en direccion de medico $codMed<br>";
    } else {
        echo "$codMed OK2<br>";
    }
}
?>