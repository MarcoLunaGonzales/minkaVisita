<?php
require('conexion.inc');
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
$vector=explode(",",$datos);
$n=sizeof($vector);
for($i=0;$i<$n;$i++)
{
	$sql_estado_preparado="select estado_salida from salida_almacenes where cod_salida_almacenes='$vector[$i]'";
	$resp_estado_preparado=mysql_query($sql_estado_preparado);
	$dat_estado_preparado=mysql_fetch_array($resp_estado_preparado);
	$estado_salida=$dat_estado_preparado[0];
	if($estado_salida==0)
	{	$sql_actualiza=mysql_query("update salida_almacenes set estado_salida='3' where cod_salida_almacenes='$vector[$i]'");
	}
	else
	{	$sql_actualiza=mysql_query("update salida_almacenes set estado_salida='0' where cod_salida_almacenes='$vector[$i]' and estado_salida='3'");
	}
}
	if($grupo_salida==1 and $cerrar!=1)
	{	echo "<script language='JavaScript'>
		alert('Se registraron los datos correctamente');
		location.href='navegador_salidamuestras.php';	
		</script>";
	}
	if($grupo_salida==1 and $cerrar==1)
	{	echo "<script language='JavaScript'>
		alert('Se registraron los datos correctamente');
		opener.location.reload();
		window.close();
		</script>";
	}
	if($grupo_salida==2 and $cerrar!=1)
	{	echo "<script language='JavaScript'>
		alert('Se registraron los datos correctamente');
		location.href='navegador_salidamateriales.php';	
		</script>";
	}
	if($grupo_salida==2 and $cerrar==1)
	{	echo "<script language='JavaScript'>
		alert('Se registraron los datos correctamente');
		opener.location.reload();
		window.close();
		</script>";
	}

?>