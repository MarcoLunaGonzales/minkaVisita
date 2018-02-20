<?php
require("estilos_gerencia.inc");
echo "<center><table border='0' class='textotit'><tr><td>Mensajes Hermes</td></tr></table></center><br>";
echo "<table border='1' class='texto' cellspacing='0' width='60%' align='center'>";
echo "<tr><th>&nbsp;</th><th>Mensaje</th><th>Fecha</th></tr>";
$sql="select cod_mensaje, mensaje, fecha_mensaje from mensajes order by fecha_mensaje desc";
$resp=mysql_query($sql);
while($dat=mysql_fetch_array($resp))
{
	$codigo=$dat[0];
	$mensaje=$dat[1];
	$fecha=$dat[2];
	echo "<tr>
	<td><input type='checkbox' name='codigo' value='$codigo'></td>
	<td>$mensaje</td>
	<td>$fecha</td></tr>";
}
echo "</table><br>";
?>