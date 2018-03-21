<?php
require("conexion.inc");
require("estilos_administracion.inc");

$txtUpd="update muestras_medicas set 
		descripcion='$muestra', cod_tipo_muestra='$tipo_muestra', codigo_linea='$linea' where codigo='$cod_muestra'";
$sql_upd=mysql_query($txtUpd);

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
			alert('Los datos no se guardaron. Contacte con el administrador.');
			history.back(-1);
			</script>";
}
?>