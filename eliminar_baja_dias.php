<?php
require("conexion.inc");
require("estilos_regional.inc");
$vector=explode(",",$datos);
$n=sizeof($vector);
for($i=0;$i<$n;$i++)
{
	$sql="delete from baja_dias where codigo_baja=$vector[$i]";
	$resp=mysql_query($sql);
	$sql_detalle="delete from baja_dias_detalle where codigo_baja=$vector[$i]";
	$resp_detalle=mysql_query($sql_detalle);
	$sql_detalle_visitador="delete from baja_dias_detalle_visitador where codigo_baja=$vector[$i]";
	$resp_detalle_visitador=mysql_query($sql_detalle_visitador);
}
echo "<script language='Javascript'>
		alert('Los datos fueron eliminados.');
		location.href='navegador_bajas_visitas.php';
		</script>";
?>