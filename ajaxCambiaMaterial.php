<?php
require('conexion.inc');
$codigo_parrilla=$_GET['parrilla'];
$prioridad=$_GET['prioridad'];
$codigo_linea=$_GET['linea'];
$id=$_GET['id'];
$codigo=$_GET['codigo'];

$sql_espe="select cod_especialidad from parrilla where codigo_parrilla='$codigo_parrilla'";
$resp_espe=mysql_query($sql_espe);
$dat_espe=mysql_fetch_array($resp_espe);
$cod_espe=$dat_espe[0];

$sql="select m.codigo_material, m.descripcion_material from material_apoyo m order by 2"; 
$resp=mysql_query($sql);
echo "<select name='sel' class='texto' onBlur='guardaMaterial(this, $codigo_parrilla, $prioridad, $id);'>";
while($dat=mysql_fetch_array($resp)){
	$codigoProd=$dat[0];
	$nombreProd=$dat[1];
	if($codigo==$codigoProd){
		echo "<option value='$codigoProd' selected>$nombreProd</option>";
	}
	else{
		echo "<option value='$codigoProd'>$nombreProd</option>";
	}
}
echo "</select>";
?>