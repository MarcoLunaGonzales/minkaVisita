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

$sql="select m.codigo, concat(m.descripcion,' ', m.presentacion) from producto_especialidad p, muestras_medicas m 
	where p.codigo_linea='$codigo_linea' and p.cod_especialidad='$cod_espe' and m.codigo=p.codigo_mm"; 
	//and m.codigo not in(select codigo_muestra from parrilla_detalle where codigo_parrilla='$codigo_parrilla');";
//echo $sql;
$resp=mysql_query($sql);
echo "<select name='sel' class='texto' onBlur='guardaSelect(this, $codigo_parrilla, $prioridad, $id);'>";
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