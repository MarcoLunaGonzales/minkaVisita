<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_ciudad from ciudades order by cod_ciudad desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo_ciudad=1000;
}
else
{	$codigo_ciudad=$dat[0];
	$codigo_ciudad++;
}
$sql_inserta=mysql_query("insert into ciudades values($codigo_ciudad,'$territorio','$tipo')");
if($sql_inserta==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_territorios.php';
			</script>";
  
}
else
{		echo "<script language='Javascript'>
			alert('No puede insertar datos duplicados.');
			history.back(-1);
			</script>";  
}
?>