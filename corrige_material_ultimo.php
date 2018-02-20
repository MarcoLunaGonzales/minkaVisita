<?php
require("conexion.inc");
$sql="select codigo, material, tipo_material, linea from material_corregido";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$material=$dat[1];
	$tipo_material=$dat[2];
	$linea=$dat[3];
	//echo "$codigo $material $tipo_material $linea<br>";
	$sql_actualiza="update material_apoyo set descripcion_material='$material', cod_tipo_material='$tipo_material',
					codigo_linea='$linea' where codigo_material='$codigo'";
	echo "$sql_actualiza";
	$resp_actualiza=mysql_query($sql_actualiza);
}
?>