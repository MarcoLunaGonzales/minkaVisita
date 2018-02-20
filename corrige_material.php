<?php
require('conexion.inc');
$sql_material="select material, nuevo_material, tipo_material, codigo_linea from material_corregir";
$resp_material=mysql_query($sql_material);
while($dat_material=mysql_fetch_array($resp_material))
{	$material=$dat_material[0];
	$material_nuevo=$dat_material[1];
	$tipo_material=$dat_material[2];
	$codigo_linea=$dat_material[3];
	echo "$material $material_nuevo $tipo_material $codigo_linea<br>";	
	
	$sql_actualiza="update material_apoyo set descripcion_material='$material_nuevo', cod_tipo_material='$tipo_material', 
	codigo_linea='$codigo_linea' where descripcion_material='$material'";
	$resp_actualiza=mysql_query($sql_actualiza);
}

?>