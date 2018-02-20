<?php
require('conexion.inc');
if($global_tipoalmacen==1)
{	require("estilos_almacenes_central.inc");
}
else
{	require("estilos_almacenes.inc");
}
$fecha_real=$fecha[6].$fecha[7].$fecha[8].$fecha[9]."-".$fecha[3].$fecha[4]."-".$fecha[0].$fecha[1];
$vector=explode(",",$datos);
$n=sizeof($vector);
for($i=0;$i<$n;$i++)
{	$sql="update salida_almacenes set estado_salida=1, despacho_fecha='$fecha_real', despacho_nroguia='$numero_guia',
	  despacho_codtipotransporte='$tipo_transporte', despacho_obs='$observaciones', despacho_nrocajas='$nro_cajas',
	  despacho_monto='$monto',despacho_peso='$peso' where cod_salida_almacenes='$vector[$i]' and grupo_salida='$tipo_material' and cod_almacen='$global_almacen'";
	//echo $sql;
	$resp=mysql_query($sql);
}
if($tipo_material==1)
{	echo "<script language='JavaScript'>
		alert('Los datos de despacho se registraron satisfactoriamente.');
		location.href='navegador_salidamuestras.php';
		</script>";	
}
else
{	echo "<script language='JavaScript'>
		alert('Los datos de despacho se registraron satisfactoriamente.');
		location.href='navegador_salidamateriales.php';
		</script>";
}
?>