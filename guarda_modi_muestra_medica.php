<?php
require("conexion.inc");
require("estilos_administracion.inc");
$sql_upd=mysql_query("update muestras_medicas set descripcion='$muestra', presentacion='$presentacion',
estado='$estado', cod_tipo_muestra='$tipo_muestra', codigo_linea='$linea' where codigo='$cod_muestra'");
if($sql_upd==1)
{
	echo "<script language='Javascript'>
			alert('Los datos fueron modificados correctamente.');
			location.href='navegador_muestras_medicas.php';
			</script>";
}
else
{
	echo "<script language='Javascript'>
			alert('No se pudieron modificar los datos.');
			history.back(-1);
			</script>";
}
?>