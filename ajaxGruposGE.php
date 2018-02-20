<?php
require('conexion.inc');
$codLinea=$_GET['codLinea'];
$sql="select g.codigo_grupo_especial, g.nombre_grupo_especial, c.descripcion 
from grupo_especial g, ciudades c where g.codigo_linea=$codLinea and g.agencia=c.cod_ciudad order by 2,3";
	$resp=mysql_query($sql);

echo "<select name='GEOrigen' class='texto' size='15'>";
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$nombre="$dat[1] ($dat[2])";
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>