<?php

require("conexion.inc");
$codCiudad=121;
$codLinea=1021;

$sql="select c.`codigo_linea`, c.`cod_med`, c.`cod_especialidad`,
	c.`categoria_med`, c.`frecuencia_linea` from `categorias_lineas` c
	where c.`codigo_linea`='$codLinea' and c.`cod_med` in (select cod_med from medicos where cod_ciudad='$codCiudad')";
$resp=mysql_query($sql);

while($dat=mysql_fetch_array($resp)){
	$codLineaMod=$dat[0];
	$codMed=$dat[1];
	$codEspe=$dat[2];
	$catMed=$dat[3];
	$frecuenciaLinea=$dat[4];
	
	
	$sqlVeri="select gd.`frecuencia` from `grilla` g, `grilla_detalle` gd
		where g.`codigo_grilla`=gd.`codigo_grilla` and g.`estado`=1 and
		gd.`cod_especialidad`='$codEspe' and gd.`cod_categoria`='$catMed' 
		and g.`codigo_linea`='$codLineaMod' and g.`agencia`=$codCiudad";
	$respVeri=mysql_query($sqlVeri);
	$frecuenciaCorrecta=mysql_result($respVeri,0,0);
	
	//upd
	$sqlUpd="update categorias_lineas set frecuencia_linea='$frecuenciaCorrecta' where 
		codigo_linea='$codLineaMod' and cod_med='$codMed' and cod_especialidad='$codEspe'";
	$respUpd=mysql_query($sqlUpd);
	
	echo $sqlUpd."<br>";
	
}

?>