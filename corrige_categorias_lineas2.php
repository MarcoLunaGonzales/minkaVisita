<?php
require("conexion.inc");

$sql_medicos="select codigo_linea, cod_med, cod_especialidad, categoria_med from categorias_lineas where codigo_linea='1021'";
$resp_medicos=mysql_query($sql_medicos);
while($dat_medicos=mysql_fetch_array($resp_medicos))
{	$codigo_linea=$dat_medicos[0];
	$cod_med=$dat_medicos[1];
	$cod_espe=$dat_medicos[2];
	$cat_med=$dat_medicos[3];

	$sqlUpd="update categorias_lineas set categoria_med='$cat_med' where cod_med='$cod_med' and codigo_linea>1031";
	echo $sqlUpd."<br>";
	$respUpd=mysql_query($sqlUpd);
	
}
echo "TODO OK";
?>