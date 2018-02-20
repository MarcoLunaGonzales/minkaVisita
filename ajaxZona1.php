<?php
require('conexion.inc');
$distrito=$_GET['distrito'];
$zona=$_GET['zona'];
$sqlZona="SELECT cod_zona, zona from zonas where cod_dist='$distrito'";
$respZona=mysql_query($sqlZona);
echo "<select name='zona1' class='texto'>";
while($datZona=mysql_fetch_array($respZona)){
	$cod_zona=$datZona[0];
	$nombre_zona=$datZona[1];
	if($cod_zona==$zona){
		echo "<option value='$cod_zona' selected>$nombre_zona</option>";
	}else{
		echo "<option value='$cod_zona'>$nombre_zona</option>";
	}
}
echo "</select>";
?>