<?php
require('conexion.inc');
$codLinea=$_GET['codLinea'];
$sql="select distinct(m.codigo), concat(m.descripcion,' ',m.presentacion)
	from grupo_especial g, muestras_medicas m
	where g.codigo_linea='$codLinea' and m.codigo=g.cod_muestra order by 2";
	$resp=mysql_query($sql);

echo "<select name='GEDestino' class='texto' size='15'>";
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$nombre="$dat[1]";
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>