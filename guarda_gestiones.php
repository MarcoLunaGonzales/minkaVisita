<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select codigo_gestion from gestiones order by codigo_gestion desc";
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
$sql_lineas="select codigo_linea, nombre_linea from lineas";
$resp_lineas=mysql_query($sql_lineas);
while($dat_lineas=mysql_fetch_array($resp_lineas))
{	$cod_linea=$dat_lineas[0];
	$nom_linea=$dat_lineas[1];
	$sql_inserta=mysql_query("insert into gestiones values($codigo,'$nombre','Inactivo','$cod_linea')");
	if($sql_inserta!=1)
	{		echo "<script language='Javascript'>
			alert('Esta ingresando datos ya existentes.');
			history.back(-1);
			</script>";
	}	
}
if($sql_inserta==1)
{	echo "<script language='Javascript'>
	alert('Los datos fueron insertados correctamente.');
	location.href='navegador_gestiones.php';
	</script>";	
}
?>