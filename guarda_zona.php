<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_zona from zonas order by cod_zona desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo_zona=1000;
}
else
{	$codigo_zona=$dat[0];
	$codigo_zona++;
}
echo $codigo_zona;
$sql_inserta="insert into zonas values($cod_territorio,$cod_distrito,$codigo_zona,'$zona')";
$resp_inserta=mysql_query($sql_inserta);
if($resp_inserta==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_zonas.php?cod_territorio=$cod_territorio&cod_distrito=$cod_distrito';
			</script>";  
}
else
{		echo "<script language='Javascript'>
			alert('No puede insertar datos duplicados.');
			history.back(-1);
			</script>";  
}
?>