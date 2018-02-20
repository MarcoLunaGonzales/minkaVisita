<?php
require('../conexion.inc');
$codProducto=$_GET['codProducto'];
$codLineaMkt=$_GET['codLineaMkt'];

$sql="select distinct(a.especialidad)
	 from asignacion_productos_excel_detalle a
	where a.linea_mkt=$codLineaMkt order by 1";
$resp=mysql_query($sql);
echo "<select name='rptEspecialidad' class='texto' size='12' multiple>";
while($dat=mysql_fetch_array($resp))
{	$codigo=$dat[0];
	echo "<option value='`$codigo`'>$codigo</option>";
}
echo "</select>";
?>