<?php
require('conexion.inc');
$distrito=$_GET['distrito'];
$sqlZona="select cod_zona, zona from zonas where cod_dist='$distrito'";
$respZona=mysql_query($sqlZona);
echo "<select name='zona1' class='texto'>";
while($datZona=mysql_fetch_array($respZona)){
	$cod_zona=$datZona[0];
	$nombre_zona=$datZona[1];
	echo "<option value='$cod_zona'>$nombre_zona</option>";	
}
echo "</select>";
?>