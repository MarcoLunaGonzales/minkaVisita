<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select codigo_linea from lineas order by codigo_linea desc";
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
$sql_inserta=mysql_query("insert into lineas values($codigo,'$linea',1,0,1)");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_lineas.php';
			</script>";
?>