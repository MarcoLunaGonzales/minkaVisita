<?php
require("conexion.inc");
require("estilos.inc");
$sql="select codigo_l_visita from lineas_visita order by codigo_l_visita desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo=1000;
}
else
{	$codigo=$dat[0];
	$codigo++;
}
	$sql_inserta=mysql_query("insert into lineas_visita values($codigo,'$nombre_linea','$global_linea')");
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_lineas_visita.php';
			</script>";		

?>