<?php
require("conexion.inc");
require("estilos_gerencia.inc");
$mensaje=$mensaje;
$sql="select max(cod_mensaje) from mensajes";
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

$fecha=date("Y-m-d H:i:s");
$sql_inserta="insert into mensajes values('$codigo','$mensaje','$fecha $hora')";
$resp_inserta=mysql_query($sql_inserta);

echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_mensajes.php';
			</script>";		
?>