<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_dist from distritos order by cod_dist desc";
$resp=mysql_query($sql);
$dat=mysql_fetch_array($resp);
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo_distrito=1000;
}
else
{	$codigo_distrito=$dat[0];
	echo $codigo_distrito;
	$codigo_distrito++;
}

$sql_inserta="insert into distritos values($cod_territorio,$codigo_distrito,'$distrito')";
$resp_inserta=mysql_query($sql_inserta);
if($resp_inserta==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_distritos.php?cod_territorio=$cod_territorio';
			</script>";
  
}
else
{		echo "<script language='Javascript'>
			alert('No puede insertar datos duplicados.');
			history.back(-1);
			</script>";  
}
?>