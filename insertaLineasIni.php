<?php
require('conexion.inc');


$codCiudadX="117";
$codMedMax=8100;

$sql="select f.codigo_funcionario, f.cod_ciudad, fli.codigo_linea 
	from funcionarios f, funcionarios_lineas fli
	where f.codigo_funcionario=fli.codigo_funcionario and 
	f.estado=1 and f.cod_cargo=1011 and f.cod_ciudad=$codCiudadX";

$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp)){

	$codFuncionario=$dat[0];
	$codCiudad=$dat[1];
	$codLinea=$dat[2];
	
	echo $codFuncionario." ".$codCiudad." ".$codLinea."<br>";
	
	$sqlCat="select cod_med, cod_especialidad, categoria_med from categorias_lineas where codigo_linea=$codLinea 
	and cod_med in (select cod_med from medicos where cod_ciudad='$codCiudadX' and cod_med>='$codMedMax') ";
	//echo $sqlCat;
	$respCat=mysql_query($sqlCat);
	while($datCat=mysql_fetch_array($respCat)){
		$codMedX=$datCat[0];
		$codEspeX=$datCat[1];
		$codCatX=$datCat[2];
		echo $codMedX."-".$codEspeX."-".$codCatX;
		
		$sqlInsert="insert into medico_asignado_visitador values('$codMedX','$codFuncionario','$codLinea')";
		echo $sqlInsert."<br>";
		$respInsert=mysql_query($sqlInsert);
	}
}
?>