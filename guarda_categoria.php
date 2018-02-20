<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql="select categoria_med from categorias_medicos where categoria_med='$categoria'";
$resp=mysql_query($sql);
$num_filas=mysql_num_rows($resp);
if($num_filas!=0)
{	echo "<script language='Javascript'>
			alert('No se puede insertar un registro con esa categoria porque ya existe.');
			location.href='navegador_categorias.php';
			</script>";		
}
else
{	$sql_inserta=mysql_query("insert into categorias_medicos values('$categoria')");
	echo "<script language='Javascript'>
			alert('Los datos fueron insertados correctamente.');
			location.href='navegador_categorias.php';
			</script>";		
}
?>