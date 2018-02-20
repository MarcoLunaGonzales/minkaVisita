<?php

require("conexion.inc");
require("estilos_vacio.inc");

//$codTerritorio=$_get['codTerritorio'];
//$cicloTrabajo=$_get['cicloTrabajo'];
//$gestionTrabajo=$_get['gestionTrabajo'];
echo "<form name='form1' action='guardarReplicarParrillaTerritorio.php' method='post'>";
echo "<input type='hidden' name='cicloTrabajo' value='$cicloTrabajo'>";
echo "<input type='hidden' name='gestionTrabajo' value='$gestionTrabajo'>";
echo "<input type='hidden' name='territorioOrigen' value='$codTerritorio'>";

$sqlNombre="select descripcion from ciudades where cod_ciudad=$codTerritorio";
$respNombre=mysql_query($sqlNombre);
$datNombre=mysql_fetch_array($respNombre);
$nombreTerritorio=$datNombre[0];
echo "<center><table border='0' class='textotit'><tr><td align='center'>Replicar Parrilla<br>Territorio Origen: $nombreTerritorio</td></tr></table></center><br>";
echo "<center><table border='1' width='80%' cellspacing='0' class='texto'><tr><th>Territorio Destino</th><th>Linea Destino</th></tr>";
$sqlTerritorio="select cod_ciudad, descripcion from ciudades order by 2";
$respTerritorio=mysql_query($sqlTerritorio);
echo "<tr><td align='center'><select name='territorioDestino' class='texto'>";
while($datTerritorio=mysql_fetch_array($respTerritorio)){
	$codCiudad=$datTerritorio[0];
	$nombreCiudad=$datTerritorio[1];	
	echo "<option value='$codCiudad'>$nombreCiudad</option>";
}
echo "</select></td>";
echo "<td align='center'><select name='lineaDestino' class='texto'>";
$sqlLineas="select codigo_linea, nombre_linea from lineas where linea_promocion=1";
$respLineas=mysql_query($sqlLineas);
while($datLineas=mysql_fetch_array($respLineas)){
	$codLinea=$datLineas[0];
	$nombreLinea=$datLineas[1];
	echo "<option value='$codLinea'>$nombreLinea</option>";		
}
echo "</select></td></tr></table></center><br>";
echo "<input type='submit' value='Replicar' class='boton'>";
echo "</form>";
echo "<center>Nota: Para realizar la réplica correctamente se borraran todas las parrillas registradas en el territorio de Destino.</center>";
?>