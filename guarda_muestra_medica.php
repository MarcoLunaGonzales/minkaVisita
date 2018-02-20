<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select codigo from muestras_medicas where codigo LIKE 'M%' ORDER BY codigo desc";
$resp=mysql_query($sql);
$max=0;
while($dat=mysql_fetch_array($resp))
{	$num_codigo=substr($dat[0],1);
	if($num_codigo>$max)
	{	$max=$num_codigo;
	}
}
$num_filas=mysql_num_rows($resp);
if($num_filas==0)
{	$codigo="M1";
}
else
{
	$max++;
	$codigo="M$max";
}
$codigo_producto=$codigo;
$sql_inserta=mysql_query("insert into muestras_medicas values('$codigo_producto','$muestra','$presentacion',1,'$tipo_muestra','$linea','')");
if($sql_inserta==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_muestras_medicas.php';
			</script>";
}
else
{
		echo "<script language='Javascript'>
			alert('Los datos no pudieron insertarse.');
			history.back(-1);
			</script>";
}
?>