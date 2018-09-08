<?php
require('conexion.inc');
$codMed=$_GET['codMed'];
$codRegion=$_GET['codRegion'];
$codCUP=$_GET['codCUP'];
$id=$_GET['id'];

//echo $id;

$sql="select c.cod_cup, c.nombre_medico from cup_medicos c where c.region='$codRegion' order by 2"; 
$resp=mysql_query($sql);
//onBlur='guardaMaterial(this, $codigo_parrilla, $prioridad, $id);'
echo "<select name='sel' class='texto'>";
while($dat=mysql_fetch_array($resp)){
	$codCUPX=$dat[0];
	$nombreMedicoX=$dat[1];
	if($codCUPX==$codCUP){
		echo "<option value='$codCUPX' selected>$nombreMedicoX</option>";
	}
	else{
		echo "<option value='$codCUPX'>$nombreMedicoX</option>";
	}
}
echo "</select><input type='button' value='Guardar' onClick='guardarRelacionCUP(sel, \"$codMed\", \"$id\")'>";
?>