<?php
require('../conexion.inc');
$codProducto=$_GET['codProducto'];
$codLineaMkt=$_GET['codLineaMkt'];

$sql="select distinct(ciudad), 
	(select c.descripcion from ciudades c where c.cod_ciudad=a.ciudad)
	 from asignacion_productos_excel_detalle a
	where a.linea_mkt=$codLineaMkt and a.producto in ('$codProducto') order by 2;";
$resp=mysql_query($sql);
echo "<select name='rptAgencia' class='texto' size='12' multiple>";
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	$nombre="$dat[1] $dat[2] $dat[3]";
	echo "<option value='$codigo'>$nombre</option>";
}
echo "</select>";
?>