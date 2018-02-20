<?php
require("conexion.inc");

/*
$sql="select codigo_linea, cod_med, cod_especialidad, categoria_med 
	from categorias_lineas c where c.cod_especialidad=''";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codLinea=$dat[0];
	$codMed=$dat[1];
	$codEspe=$dat[2];
	echo "$codLinea $codMed $codEspe";
	
	$sqlVeri="select rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and  rc.codigo_ciclo in (1,2) and rc.codigo_gestion=1012 and
		rc.codigo_linea=$codLinea and rd.cod_med=$codMed";
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);
	if($numFilas==0){
		mysql_query("delete from categorias_lineas where cod_med=$codMed and codigo_linea=$codLinea");
		echo "borrar <br>";
	}
	else{
		$codEspeVeri=mysql_result($respVeri,0,0);
		echo $codEspeVeri;
	}
}
*/


$sql="select codigo_linea, cod_med, cod_especialidad, categoria_med 
	from categorias_lineas c where c.categoria_med=''";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){
	$codLinea=$dat[0];
	$codMed=$dat[1];
	$codEspe=$dat[2];
	echo "$codLinea $codMed $codEspe";
	
	$sqlVeri="select rd.cod_especialidad, rd.categoria_med from rutero_maestro_cab_aprobado rc, rutero_maestro_aprobado rm, rutero_maestro_detalle_aprobado rd
		where rc.cod_rutero=rm.cod_rutero and rm.cod_contacto=rd.cod_contacto and  rc.codigo_ciclo in (1,2) and rc.codigo_gestion=1012 and
		rc.codigo_linea=$codLinea and rd.cod_med=$codMed";
	$respVeri=mysql_query($sqlVeri);
	$numFilas=mysql_num_rows($respVeri);
	if($numFilas==0){
		mysql_query("delete from categorias_lineas where cod_med=$codMed and codigo_linea=$codLinea");
		echo "borrar <br>";
	}
	else{
		$codEspeVeri=mysql_result($respVeri,0,0);
		echo $codEspeVeri;
	}
}

?>