<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select cod_cargo from cargos order by cod_cargo desc";
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
$sql_inserta=mysql_query("insert into cargos values($codigo,'$cargo')");
echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_cargos.php';
			</script>";
?>